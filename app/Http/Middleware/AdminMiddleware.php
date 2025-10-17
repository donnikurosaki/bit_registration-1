<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect()->route('login')->with('error', 'Accès non autorisé.');
            }
    
            return $next($request);
    }
}