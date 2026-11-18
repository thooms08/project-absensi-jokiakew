<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /* 
         * X-Frame-Options: DENY
         * Mencegah clickjacking dengan melarang page dimuat dalam iframe.
         */
        $response->headers->set('X-Frame-Options', 'DENY');

        /* 
         * X-Content-Type-Options: nosniff
         * Mencegah MIME type sniffing — browser harus menghormati Content-Type.
         */
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        /* 
         * Referrer-Policy: strict-origin-when-cross-origin
         * Batasi informasi Referer yang dikirim ke situs eksternal.
         */
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
