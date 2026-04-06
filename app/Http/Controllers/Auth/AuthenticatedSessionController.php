<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        activity('auth')
            ->causedBy($user)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ])
            ->log('Inicio de sesion administrativo');

        Log::info('Inicio de sesion Collcapujllay', [
            'email' => $user->email,
            'ip' => $request->ip(),
        ]);

        if ($request->expectsJson()) {
            return redirect()->intended(route('dashboard'));
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Provide real-time validation for login fields.
     */
    public function validateLogin(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->only('email', 'password'),
            [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'min:6'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        return response()->json(['message' => 'ok']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
