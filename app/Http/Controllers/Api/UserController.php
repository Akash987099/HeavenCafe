<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\Wallet;
use App\Models\Address;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $user;
    protected $address;

    public function __construct()
    {
        $this->user = new User();
        $this->address = new Address();
    }

    public function addAddress(Request $request)
    {
        dd($request->all());
    }

    public function walletPoints()
    {
        $user = auth()->user();

        return response()->json([
            'status' => true,
            'points' => $user->wallet_points,
        ]);
    }

    /**
     * Convert loyalty points into wallet balance.
     *
     * The current conversion rate is fixed at 100 points = Rs. 1.
     */
    public function redeemWallet(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'points' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $pointsToRedeem = (float) $request->points;
        $pointValue = 0.01; // 100 loyalty points = Rs. 1

        return DB::transaction(function () use ($pointsToRedeem, $pointValue) {
            // Lock the user row so concurrent redeem requests cannot credit twice.
            $user = User::whereKey(auth('api')->id())->lockForUpdate()->first();

            if (! $user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized user',
                ], 401);
            }

            $availablePoints = (float) Wallet::where('user_id', $user->id)
                ->selectRaw("COALESCE(SUM(CASE WHEN type = 'credit' THEN points WHEN type = 'debit' THEN -points ELSE 0 END), 0) as balance")
                ->value('balance');

            if ($pointsToRedeem > $availablePoints) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient loyalty points',
                    'available_points' => $availablePoints,
                ], 422);
            }

            $redeemedAmount = round($pointsToRedeem * $pointValue, 2);

            Wallet::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'points' => $pointsToRedeem,
                'description' => "Redeemed {$pointsToRedeem} loyalty points to wallet (Rs. {$redeemedAmount})",
            ]);

            $user->increment('wallet_points', $redeemedAmount);

            return response()->json([
                'status' => true,
                'message' => 'Points added to wallet successfully',
                'data' => [
                    'redeemed_points' => $pointsToRedeem,
                    'point_value' => $pointValue,
                    'redeemed_amount' => $redeemedAmount,
                    'remaining_loyalty_points' => $availablePoints - $pointsToRedeem,
                    'wallet_balance' => (float) $user->fresh()->wallet_points,
                ],
            ], 200);
        });
    }

    public function notifications()
    {
        $user = auth()->user();

        $notification = Notification::where('user_id', $user->id)->select('id', 'title', 'description')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => true,
            'notifications' => $notification,
        ]);
    }

    public function notificationDetails($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user',
            ], 401);
        }

        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->select('id', 'title', 'description', 'is_read')
            ->orderBy('id', 'desc')
            ->first();

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        if ($notification->is_read == 0) {
            $notification->update(['is_read' => 1]);
        }

        return response()->json([
            'status' => true,
            'notification' => $notification,
        ], 200);
    }

    public function loyaltyPoints()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user',
            ], 401);
        }

        $walletPoints = Wallet::where('user_id', $user->id)
            // ->where('is_processed', 1)
            ->with(['order:id,order_no'])
            ->select('id', 'order_id', 'points', 'type', 'description', 'is_processed', 'expiry_date', 'created_at')
            ->orderBy('id', 'desc')
            ->get();

        $availablePoints = 0;

        foreach ($walletPoints as $point) {

            // if (!empty($point->expiry_date) && \Carbon\Carbon::parse($point->expiry_date)->isPast()) {
            //     continue;
            // }

            if ($point->type == 'credit') {
                $availablePoints += $point->points;
            }
            elseif ($point->type == 'debit') {
                $availablePoints -= $point->points;
            }

            $point->order_no = $point->order->order_no ?? null;
            unset($point->order);
        }

        return response()->json([
            'status' => true,
            'available_points' => $availablePoints,
            'points' => $walletPoints,
        ], 200);
    }

    public function profile()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'user' => $user,
        ], 200);
    }

    public function editProfile(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized user',
            ], 401);
        }

        $data = [];

        if ($request->filled('name')) {
            $request->validate([
                'name' => 'string|max:255',
            ]);

            $data['name'] = $request->name;
        }

        if ($request->filled('phone')) {
            $request->validate([
                'phone' => 'string|max:20|unique:users,phone,' . $user->id,
            ]);

            $data['phone'] = $request->phone;
        }

        if ($request->hasFile('image')) {

            $request->validate([
                'image' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();

            $request->file('image')->move(public_path('profile'), $imageName);

            $data['image'] = 'profile/' . $imageName;
        }

        if (empty($data)) {
            return response()->json([
                'status' => false,
                'message' => 'No data provided for update',
            ], 200);
        }

        $user->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(),
        ], 200);
    }
}
