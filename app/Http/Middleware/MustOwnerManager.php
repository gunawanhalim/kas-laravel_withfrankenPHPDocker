<?php
//php artisan make:middleware MustAdmin
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MustOwnerManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        // Periksa apakah pengguna memiliki peran "Owner" atau "Manager"
        if (Auth::check() && (Auth::user()->role == "Owner" || Auth::user()->role == "Manager")) {
            // Jika ya, lanjutkan dengan proses berikutnya
            return $next($request);
        }
        
        abort(404, 'Unauthorized');
    }
}
