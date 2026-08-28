<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBusinessService
{
    public function getAccounts($accessToken)
    {
        return Http::withToken($accessToken)
            ->acceptJson()
            ->get('https://mybusinessaccountmanagement.googleapis.com/v1/accounts');
    }
}