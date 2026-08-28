<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use App\Services\GoogleBusinessService;

class GoogleBusinessController extends Controller
{
    public function accounts(GoogleBusinessService $service)
    {
        $social = SocialAccount::where('user_id', auth()->id())
            ->where('provider', 'google')
            ->first();

        if (!$social) {
            return response()->json([
                'message' => 'Google account not connected.'
            ], 404);
        }

        $response = $service->getAccounts($social->access_token);

        return response()->json([
            'status' => $response->status(),
            'data'   => $response->json(),
        ]);
    }
}