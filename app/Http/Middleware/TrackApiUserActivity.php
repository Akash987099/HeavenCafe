<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TrackApiUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if ($user) {
            $onlineUserKey = 'online-user-' . $user->id;
            $registryKey = 'online-users-registry';
            $registry = collect(Cache::get($registryKey, []))
                ->push($user->id)
                ->unique()
                ->values()
                ->all();

            Cache::put($onlineUserKey, [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'last_seen_at' => now()->toDateTimeString(),
                'last_seen_unix' => now()->timestamp,
            ], now()->addMinutes(10));

            Cache::forever($registryKey, $registry);
        }

        return $next($request);
    }
}
