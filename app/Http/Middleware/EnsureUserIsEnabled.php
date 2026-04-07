<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsEnabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si no hay usuario autenticado, deja pasar
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Si el usuario está deshabilitado
        if (!$user->estado) {

            Auth::logout();

            // Invalidar sesión completamente
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('auth_disabled_message', 'Su cuenta ha sido inhabilitada. Comuníquese con administración.');
        }

        return $next($request);
    }
}
