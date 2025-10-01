<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthenticatedTokenController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Credenciais válidas: emita um token
            $token = $user->createToken('remember_token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'user' => $user,
            ]);
        }

        return response()->json(['error' => 'Credenciais inválidas'], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        // Revoga o token usado na requisição atual
        $request->user()->currentAccessToken()->delete();
        // Ou revoga todos os tokens do usuário: $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}