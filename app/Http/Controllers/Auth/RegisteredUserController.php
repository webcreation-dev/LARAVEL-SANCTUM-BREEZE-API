<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            Auth::login($user);

            return response()->json(['message' => 'Inscription reussie']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Échec de l inscription', 'errors' => $e->errors()], 422);
        }

    }

    // FORGOT PASSWORD

    /**
     * Créer un nouvel utilisateur.
     *
     * @bodyParam email string required Adresse e-mail de l'utilisateur.
     */
    public function forgotPassword(Request $request)
    {
        try {
           $request->validate([
               'email' => 'required|email',
           ]);
           if (!User::where('email', $request->only('email'))->exists()) {
               return response()->json(['error' => 'Adresse e-mail non trouvée dans la base de données'], 422);
           }
           $status = Password::sendResetLink(
               $request->only('email')
           );

           if ($status == Password::RESET_LINK_SENT) {
               return response()->json(['message' => 'Lien de réinitialisation envoyé avec succès']);
           } else {
               return response()->json(['error' => 'Échec de l\'envoi du lien de réinitialisation'], 422);
           }
       } catch (ValidationException $e) {
           return response()->json(['error' => 'Erreur de validation', 'errors' => $e->errors()], 422);
       }
    }

    // RESET PASSWORD
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Mot de passe réinitialisé avec succès']);
        } else {
            if ($status === Password::INVALID_TOKEN) {
                return response()->json(['error' => 'Token de réinitialisation invalide'], 422);
            } elseif ($status === Password::INVALID_USER) {
                return response()->json(['error' => 'Adresse e-mail non trouvée'], 422);
            } else {
                return response()->json(['error' => 'Échec de la réinitialisation du mot de passe'], 422);
            }
        }
    }

    // LIST USERS
    public function list(Request $request)
    {
        $data = User::all();
        return self::apiResponse(true, "Liste des utilisateurs", $data);
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
