<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\SessionIdChanged;
use Illuminate\Support\Facades\Event;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('/');
        }
    }
    public function handle($request, Closure $next, ...$guards)
    {
        // Cek apakah pengguna telah login
        if (Auth::check()) {
            $user = Auth::user();
            
            // Ambil remember_token yang disimpan di sesi
            $remember_token = $request->session()->get('remember_token');

            // Jika remember_token saat ini tidak sama dengan remember_token yang tersimpan di sesi
            if ($remember_token !== $user->remember_token) {
                // Logout pengguna
                Auth::logout();
                
                // Jika pengguna lain mencoba login, kirim pesan bahwa sesi telah diakhiri
                if (!$request->session()->has('logout_message')) {
                // Di dalam metode handle middleware
                event(new SessionIdChanged($user->id));
                return redirect()->route('login')->with('logout_message', 'Sesi Anda telah berakhir karena login dari tempat lain.');
                }
            }
            // Periksa timeout sesi
            $lastLoginTime = Carbon::parse($request->session()->get('tanggal_login'));
            $currentTime = Carbon::now();
            $timeoutDuration = config('auth.session_timeout_duration', 60); // Waktu timeout dalam menit (default: 60 menit)

            // Jika waktu login terakhir lebih dari 1 jam yang lalu, logout pengguna
            if ($lastLoginTime->diffInMinutes($currentTime) > $timeoutDuration) {
                Auth::logout();
                return redirect()->route('login')->with('logout_message', 'Sesi Anda telah berakhir karena tidak aktif.');
            }
        } else {
            return redirect('/');
        }


        return $next($request);
    } 
}
