<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\UserCard;

class PaymentMethodController extends Controller
{
    protected $paymentMethod;
    protected $card;

    public function __construct()
    {
        $this->paymentMethod = new PaymentMethod();
        $this->card = new UserCard();
    }

    public function index()
    {
        $paymentMethod = PaymentMethod::select('id', 'name', 'label', 'description', 'icon', 'badge', 'status')
            ->orderBy('status', 'desc')
            ->get();

        $user = auth()->user();

        $cards = $this->card
            ->select('id', 'card_number', 'card_name', 'balance', 'status', 'is_primary', 'expiry_date')
            ->where('user_id', $user->id)
            ->orderBy('is_primary', 'DESC')
            ->get();

        foreach ($paymentMethod as $method) {

            if ($method->name == 'wallet') {
                $method->badge = 'You have ₹' . ($user->wallet_points ?? 0) . ' in your wallet';
            }

            if ($method->name == 'card') {

                if ($cards->isEmpty()) {
                    $method->status = 0;
                    $method->cards = [];
                } else {
                    $method->cards = $cards;
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment Methods',
            'data' => $paymentMethod
        ]);
    }
}
