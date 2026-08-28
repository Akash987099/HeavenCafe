<?php

namespace App\Http\Controllers\Rider;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Supplier;

class RiderController extends Controller
{
    protected $supplier;

    public function __construct()
    {
        $this->supplier = new Supplier();
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email'
        ]);

        $user = Supplier::where('email', $request->email)->first();

        dd($user);exit();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        if ($user->email_verify == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Email not verified. OTP verification pending.'
            ], 403);
        }

        if (is_null($user->email_verify)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired. Please request a new OTP.'
            ], 403);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $registryKey = 'online-users-registry';
        $registry = collect(Cache::get($registryKey, []))
            ->push($user->id)
            ->unique()
            ->values()
            ->all();

        Cache::put('online-user-' . $user->id, [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'last_seen_at' => now()->toDateTimeString(),
            'last_seen_unix' => now()->timestamp,
        ], now()->addMinutes(10));
        Cache::forever($registryKey, $registry);

        return response()->json([
            'status' => true,
            'token' => $token,
            'data'  => $user,
        ], 200);
    }
    
}
