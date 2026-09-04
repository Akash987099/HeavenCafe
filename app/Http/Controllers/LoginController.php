<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Customer;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function logins(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'redirect' => route('index'),
                ]);
            }

            return redirect()->route('index');
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid email or password.',
            ], 422);
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->onlyInput('email');
    }
    public function loginForm(){
        return view('auth.user-login');
    }
    public function loginPost(Request $request){
        $credentials = $request->only('username', 'password');

        if (Auth::guard('user')->attempt($credentials)) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid username or password.']);
    }
    public function loginClient(Request $request){
        return view('auth.client-login');
    }
    public function loginsClient(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::guard('client')->attempt($credentials)) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid username or password.']);
    }

    public function userlogin(){
        return view('auth.user-login');
    }

    public function userlogins(Request $request){
        // echo '1111';
        // exit();
        $credentials = $request->only('email', 'password');

        if (Auth::guard('user')->attempt($credentials)) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid username or password.']);
    }

    public function loginPos(){
        return view('pos.login');
    }

    public function loginsPos(Request $request){
        $credentials = $request->only('email', 'password');
        // dd($credentials);
        
        if (Auth::guard('pos')->attempt($credentials)) {
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error', 'message' => 'Invalid username or password.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('pos')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('pos.login');
    }
}
