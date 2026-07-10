<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isProfileComplete()) {
            if (! $request->routeIs('onboarding.*')) {
                return redirect()->route('onboarding.profile');
            }
        }

        return $next($request);
    }
}
