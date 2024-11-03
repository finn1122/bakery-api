<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
class JWTAuthController extends Controller
{
    // User login
    public function login(Request $request)
    {
        Log::info('login');
        $credentials = $request->only('email', 'password');

        try {
            // Intentar autenticar al usuario con las credenciales proporcionadas.
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            // Obtener el usuario autenticado.
            $user = auth()->user();

            // Verificar si el usuario ha verificado su correo electrónico.
            if (!$user->hasVerifiedEmail()) {
                return response()->json(['error' => 'Email not verified'], 403); // Código de estado 403 para prohibido.
            }

            // Verificar si el usuario está activo.
            if (!$user->active) {
                return response()->json(['error' => 'User account is inactive'], 403); // Usuario inactivo.
            }

            // (opcional) Adjuntar el rol al token.
            $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

            return response()->json(compact('token'));
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    }

    // Get authenticated user
    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    // User logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }
}
