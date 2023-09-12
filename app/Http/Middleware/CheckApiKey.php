<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key=$request->header('x-api-key');
        if(!$key || $key  !=config('services.api_key')){
            return FacadesResponse::json([
                'message'=>'Missing or invalid API key.'
            ],400);
        }
        return $next($request);
    }
}
