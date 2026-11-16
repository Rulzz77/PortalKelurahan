<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('admin.login')->with('info', 'Silakan masuk untuk membuka dashboard.');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses hanya tersedia untuk administrator.');
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'private, no-store, max-age=0');

        return $response;
    }
}
