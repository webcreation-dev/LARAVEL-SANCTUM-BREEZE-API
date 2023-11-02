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
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('myapptoken')->plainTextToken;
            return self::apiResponse(true, "Connexion reussie", [$user, $token]);
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
     *
     * @bodyParam email string required Adresse e-mail de l'utilisateur.
     */
    public function destroy(Request $request)
    {
        // Auth::guard('web')->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();
        $user = User::where('email', $request->email)->first();
        $user->tokens()->delete();

        return self::apiResponse(true, "Déconnexion réussie");
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
