<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateN8nToken
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-API-Token') !== config('app.n8n_api_token')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
