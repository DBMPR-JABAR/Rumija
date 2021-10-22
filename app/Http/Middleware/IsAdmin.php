<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
{
     if (Auth::user() &&  Auth::user()->id == 1) {
            return $next($request);
     }
     Log::warning(Auth::user()->name .' mencoba mengakses logs');

    return redirect('/');
}
}
