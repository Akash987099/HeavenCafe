<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\CardType;
use App\Models\UserCard;
use App\Models\Notification;
use App\Models\CardTransaction;

class LeadController extends Controller
{
    protected $lead;
    protected $notification;
    protected $cardType;
    protected $userCard;
    protected $cardTransaction;

    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
        $this->notification = new Notification();
        $this->cardType = new CardType();
        $this->userCard = new UserCard();
        $this->cardTransaction = new CardTransaction();
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'card_type_id' => 'required|integer|exists:card_types,id',
        ]);

        $userData = auth()->user();

        $exists = $this->lead
            ->where('user_id', $userData->id)
            ->where('card_type_id', $data['card_type_id'])
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already applied for this card. Please wait for approval.'
            ], 400);
        }

        $crn = 'CRN' . date('YmdHis') . rand(100, 999);

        $lead = $this->lead->create([
            'user_id' => $userData->id,
            'card_type_id' => $data['card_type_id'],
            'crn' => $crn,
            'name' => $userData->name,
            'phone' => $userData->phone,
            'email' => $userData->email,
            'status' => 'pending',
        ]);

        $this->notification->create([
            'user_id' => $userData->id,
            'title' => 'New Lead Application',
            'message' => "Your lead application with CRN: $crn has been submitted successfully.",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead application submitted successfully',
            'crn' => $crn
        ], 201);
    }

    public function index()
    {
        $userId = auth()->id();
        // dd($userId);

        $leads = $this->lead
            ->with('cardType')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $leads
        ]);
    }

    public function show($id)
    {
        $userId = auth()->id();

        $lead = $this->lead
            ->with('cardType')
            ->where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lead
        ]);
    }

    public function myCards()
    {
        $userId = auth()->id();

        $userCards = $this->userCard
            ->with('cardType')
            ->where('user_id', $userId)
            ->get();

        $takenCardTypeIds = $userCards->pluck('card_type_id')->toArray();

        $leads = $this->lead
                ->with('cardType')
                ->where('user_id', $userId)
                ->where('status', '!=', 'completed')
                ->whereNotIn('card_type_id', $takenCardTypeIds)
                ->get();

        $leadCardTypeIds = $leads->pluck('card_type_id')->toArray();

        $excludeIds = array_merge($takenCardTypeIds, $leadCardTypeIds);

        $remainingCardTypes = \App\Models\CardType::where('status', 1)
            ->whereNotIn('id', $excludeIds)
            ->get();

        return response()->json([
            'success' => true,
            'applied_cards' => $userCards,
            'available_card_types' => $remainingCardTypes,
            'leads' => $leads
        ]);
    }

    public function setPrimaryCard($id)
    {
        $userId = auth()->id();

        $card = $this->userCard
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$card) {
            return response()->json([
                'success' => false,
                'message' => 'Card not found'
            ], 404);
        }

        $this->userCard
            ->where('user_id', $userId)
            ->update(['is_primary' => 0]);

        $this->userCard
            ->where('id', $id)
            ->update(['is_primary' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Primary card updated successfully'
        ]);
    }

    public function transactionHistory()
    {
        $userId = auth()->id();

        $transactions = $this->cardTransaction
            // ->with('card')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }
}
