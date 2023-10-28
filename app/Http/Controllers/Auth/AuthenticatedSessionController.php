<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Connecter un utilisateur
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();
            return response()->json(['message' => 'Connexion reussie']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Échec de la connexion', 'errors' => $e->errors()], 422);
        } catch (AuthenticationException $e) {
            return response()->json(['message' => 'Échec de la connexion : identifiants incorrects'], 401);
        }
    }

    /**
     * Déconnecter un utilisateur
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

}
