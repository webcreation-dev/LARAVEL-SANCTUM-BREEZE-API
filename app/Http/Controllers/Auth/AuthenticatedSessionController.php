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
            return self::apiResponse(true, "Connexion reussie");
            // return response()->json(['message' => 'Connexion reussie']);
        } catch (ValidationException $e) {
            return self::apiResponse(false, "Échec de la connexion");
            // return response()->json(['message' => 'Échec de la connexion', 'errors' => $e->errors()], 422);
        } catch (AuthenticationException $e) {
            return self::apiResponse(false, "Échec de la connexion : identifiants incorrects");
            // return response()->json(['message' => 'Échec de la connexion : identifiants incorrects'], 401);
        }
    }

    /**
     * Déconnecter un utilisateur
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return self::apiResponse(true, "Déconnexion réussie");
        // return response()->noContent();
    }

    public static function apiResponse($success, $message, $data = [], $status = 200) //: array
    {
        $response = response()->json([
            'success' => $success,
            'message' => $message,
            'body' => $data
        ], $status);
        return $response;
    }

}
