<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\CardType;
use App\Models\Notification;
use App\Models\UserCard;

class LeadController extends Controller
{
    private $lead;
    private $cardType;
    private $notification;
    private $userCard;

    public function __construct()
    {
        $this->lead = new Lead();
        $this->cardType = new CardType();
        $this->notification = new Notification();
        $this->userCard = new UserCard();
    }

    public function index()
    {
        $leads = $this->lead->orderBy('id', 'desc')->paginate(config('constants.pagination_limit'));
        return view('leads.index', compact('leads'));
    }

    public function view($id)
    {
        $lead = $this->lead->findOrFail($id);
        return view('leads.view', compact('lead'));
    }

    public function updateStatus(Request $request, $id)
    {
        $status = $request->input('status');
        $lead = $this->lead->findOrFail($id);
        $lead->status = $status;
        $lead->save();

        $this->notification->create([
            'user_id' => $lead->user_id,
            'message' => "Your lead status has been updated to: " . ucfirst($status),
        ]);

        if ($status === 'completed') {

            $cardTypeName = strtolower($lead->cardType->name ?? '');

            if (str_contains($cardTypeName, 'silver')) {
                $prefix = 'SLV';
            } elseif (str_contains($cardTypeName, 'gold')) {
                $prefix = 'GLD';
            } elseif (str_contains($cardTypeName, 'platinum')) {
                $prefix = 'PLT';
            } else {
                $prefix = 'GEN';
            }

            $number = str_pad($lead->id, 6, '0', STR_PAD_LEFT);

            $cardNumber = 'AWC-' . $prefix . '-' . $number;

            $this->userCard->where('user_id', $lead->user_id)->update(['is_primary' => 0]);

            $this->userCard->create([
                'user_id' => $lead->user_id,
                'name' => $lead->name,
                'mobile' => $lead->phone,
                'email' => $lead->email,
                'card_type_id' => $lead->card_type_id,
                'card_number' => $cardNumber,
                'card_name' => $lead->cardType->name ?? 'Unknown',
                'balance' => 10,
                'status' => 1,
                'is_primary' => 1,
                'expiry_date' => now()->addDay(30),
            ]);
        }

        return redirect()->route('leads.view', $id)->with('success', 'Lead status updated successfully.');
    }
}
