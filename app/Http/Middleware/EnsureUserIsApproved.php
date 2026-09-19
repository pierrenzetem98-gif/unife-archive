<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Vérification directe de l'admin ou du statut approuvé
            if ($user->role === 'admin' || (bool) $user->is_approved) {
                return $next($request);
            }

            return redirect()->route('approval.notice');
        }

        return redirect()->route('login');
    }
}