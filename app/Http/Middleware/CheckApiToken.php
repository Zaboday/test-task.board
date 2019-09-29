<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     * Провекра api_token пришедшего в запросе по БД. Если нет юзера с таким api_token - возращаем Unauthorized.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestHasToken = false;
        if (null !== $request->bearerToken() || null !== $request->input('api_token')) {
            $requestHasToken = true;
        }
        if ($requestHasToken && Auth::guard('api')->user()) {
            return $next($request);
        }

        return response()->json(['message' => 'Invalid token.'], 401);
    }
}
