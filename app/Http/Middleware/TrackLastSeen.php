<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrackLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user   = auth()->user();
            $cacheKey = 'last_seen_' . $user->id;

            // Only update DB every 2 minutes to avoid hammering the database
            if (!Cache::has($cacheKey)) {
                $user->timestamps = false; // don't update updated_at
                $user->last_seen_at = now();
                $user->save();
                Cache::put($cacheKey, true, now()->addMinutes(2));
            }
        }

        return $next($request);
    }
}
