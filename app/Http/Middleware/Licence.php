<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Licence
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $jwks = Cache::remember('jwks', 60 * 60 * 24, function () { // 24h cache
                return Http::get('https://raw.githubusercontent.com/SySafarila/jwks/main/jwks.json')->json();
            });
            JWT::decode(config('app.licence'), JWK::parseKeySet($jwks));
            return $next($request);
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json(['message' => $th->getMessage()]);
        }
    }
}
