<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('/login'); 
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->role === $role) {
            return $next($request);     
        }

        if ($user->role === 'penulis') {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return abort(403, 'Unauthorized'); // Akses ditolak
    }
}
