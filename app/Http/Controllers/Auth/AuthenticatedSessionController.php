<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        // Validação dos dados de entrada
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tentativa de autenticação
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('remember_token')->plainTextToken;

            return response()->json([
                'message' => 'Login realizado com sucesso!',
                'token' => $token,
                'user' => $user,
            ], 200);
        }

        // Se a autenticação falhar
        return response()->json([
            'message' => 'Credenciais inválidas.',
        ], 401);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
