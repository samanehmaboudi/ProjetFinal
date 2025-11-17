<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Si l'usager est déjà connecté, on le redirige
     * vers sa page principale (celliers).
     */
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        // Si aucun guard n'est passé, on utilise le guard par défaut.
        $guards = $guards === [] ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Usager déjà authentifié: pas besoin d'aller sur /login ou /register
                return redirect()->route('celliers.index');
            }
        }

           // Usager NON authentifié: il peut continuer vers la route demandée (login/register)
        return $next($request);
    }
}
