<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLoginStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {
         // Memeriksa apakah pengguna masuk dan statusnya diaktifkan
         if (Auth()->check() && Auth()->user()->status_aktif == "1") {
            return $next($request);
        }

        // Jika tidak, arahkan pengguna ke halaman yang sesuai
        return redirect()->route('Auth.disabled');
    }
}
