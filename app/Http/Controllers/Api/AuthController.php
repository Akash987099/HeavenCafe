<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResetPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\Wallet;
use App\Models\Notification;
use App\Models\Address;
use Carbon\Carbon;  
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    protected $user;
    protected $reset;
    protected $wallet;
    protected $notification;
    protected $address;

    public function __construct()
    {
        $this->user = new User();
        $this->reset = new ResetPassword();
        $this->wallet = new Wallet();
        $this->notification = new Notification();
        $this->address = new Address();
    }

    public function register(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->email)),
        ]);

        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Email already registered',
                'errors' => [
                    'email' => ['This email is already registered.'],
                ],
            ], 422);
        }

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'email_verify' => 1,
            'wallet_points' => 50,
            'password'     => Hash::make($request->password),
        ]);

        $this->wallet->create([
            'user_id' => $user->id,
            'type' => 'credit',
            'points' => 50,
            'is_processed' => 1,
            'description' => 'Welcome bonus for new registration',
            'expiry_date' => Carbon::now()->addDays(28),
        ]);

        $this->notification->create([
            'user_id' => $user->id,
            'title' => 'Welcome to Cafe Loyalty Program',
            'link' => null,
            'description' => 'You have received 50 welcome points. Start earning more rewards with us!',
            'is_read' => 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully'
        ], 200);
    }

    public function registerOld(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)
            ->where('email_verify', 0)
            ->first();

        $otp = rand(1000, 9999);

        if ($user) {

            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            $emailStatus = send_email(
                $user->email,
                'email_verify',
                ['OTP' => $otp]
            );

            send_whatsapp_otp($user->phone, $otp);

            return response()->json([
                'status' => true,
                'message' => 'OTP resent successfully'
            ], 200);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // email send
        send_email(
            $user->email,
            'email_verify',
            ['OTP' => $otp]
        );

        // whatsapp send
        send_whatsapp_otp($user->phone, $otp);

        $token = auth('api')->login($user);

        return response()->json([
            'status' => true,
            'message' => 'User registered. OTP sent to Email & WhatsApp.'
        ], 201);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Email not registered.',
            ], 404);
        }

        if ($user->status == 1) {
            return response()->json([
                'status' => false,
                'message' => 'Email already verified.',
            ], 409);
        }

        if ($user->otp_expires_at && $user->otp_expires_at->isFuture()) {
            return response()->json([
                'status' => false,
                'message' => 'OTP already sent. Please wait before requesting again.',
            ], 429);
        }

        $otp = rand(1000, 9999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        send_email(
            $user->email,
            'resend_otp',
            [
                'OTP' => $otp
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'OTP resent successfully.',
        ], 200);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('otp_expires_at', '>=', now())
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'OTP is invalid or expired'
            ], 400);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verify' => 1,
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

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

        $is_address = $this->address->where('user_id', $user->id)->exists();

        return response()->json([
            'status' => true,
            'token' => $token,
            'is_address'  => $is_address ? true : false,
            'data'  => $user,
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = $this->user->where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'No account found with this email address.',
            ], 404);
        }

        $otp = rand(1000, 9999);

        $this->reset->where('user_id', $user->id)->delete();

        $this->reset->create([
            'user_id'        => $user->id,
            'otp'            => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        send_email(
            $user->email,
            'email_verify',
            [
                'OTP' => $otp
            ]
        );


        return response()->json([
            'status'  => true,
            'message' => 'OTP has been sent to your email address.',
        ], 200);
    }

    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $reset = $this->reset->where('otp', $request->otp)->where('otp_expires_at', '>=', now())->first();

        if (!$reset) {
            return response()->json([
                'status' => false,
                'message' => 'OTP is invalid or expired'
            ], 400);
        }

        $newPassword = Str::random(8);

        $this->user->where('email', $request->email)->update([
            'password' => Hash::make($newPassword),
        ]);

        send_email(
            $request->email,
            'new_password',
            [
                'PASSWORD' => $newPassword
            ]
        );

        return response()->json([
            'status' => true,
            'email'  => $request->email,
            'password' => $newPassword,
            'message' => 'Email verified successfully'
        ]);
    }

    public function logout()
    {
        $user = auth('api')->user();

        if ($user) {
            Cache::forget('online-user-' . $user->id);
        }

        auth()->logout();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}
