<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
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
     * RECUPERER UN UTTILISATEUR
     *
     * @bodyParam email string required Email de l'employé
     */
    // public function getUser(Request $request)
    // {
    //     $user = User::email($request->email)->first();
    //     return self::apiResponse(true, "Information de l'utilisateur", $user);
    // }

    /**
     * LISTE DES UTILISATEURS
     *
     */
    public function getAllUsers()
    {
        $users = User::all();
        return self::apiResponse(true, "Liste des utilisateurs", $users);
    }

    /**
     * LISTE DES EMPLPOYES
     *
     */
    public function getAllEmployees()
    {
        $employees = User::where('profil_id', 2)->get();
        return self::apiResponse(true, "Liste des employés", $employees);
    }
    /**
     * ENREGISTRER UN NOUVEL UTILISATEUR
     *
     * @bodyParam name string required Nom
     * @bodyParam email string required Email
     * @bodyParam password string required Mot de passe
     * @bodyParam password_confirmation string required Confirmation du mot de passe
     *
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
                'profil_id' => 1,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));

            $token = $user->createToken('myapptoken')->plainTextToken;
            $userResponse = User::find($user->id);
            $userResponse->token = $token;

            return self::apiResponse(true, "Inscription reussie", $userResponse);
        } catch (ValidationException $e) {
            return self::apiResponse(false, "Échec de l inscription");
        }
    }

    /**
     * CREER UN NOUVEL EMPLOYE
     *
     * @bodyParam name string required Nom
     * @bodyParam email string required Email
     *
     */
    public function createEmployee(Request $request) {

        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);

            $user = User::create([
                'profil_id' => 2,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
            ]);

            $user = User::find($user->id);
            $status = Password::sendResetLink([
                'email' => $user->email
            ]);

            if ($status == Password::RESET_LINK_SENT) {
                 return self::apiResponse(true, "Nouvel employé créée avec succès");
            } else {
                 throw ValidationException::withMessages([
                     'email' => [__($status)],
                 ]);
                 return self::apiResponse(false, "Échec de la création de l'employé");
            }
        } catch (ValidationException $e) {
            return self::apiResponse(false, "Échec de l inscription de l'employé");
        }
    }

    // FORGOT PASSWORD
    /**
     * MOT DE PASSE OUBLIE
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
                return self::apiResponse(false, "Adresse e-mail non trouvée dans la base de données");
           }
           $status = Password::sendResetLink(
               $request->only('email')
           );

           if ($status == Password::RESET_LINK_SENT) {
                return self::apiResponse(true, "Lien de réinitialisation envoyé avec succès");
           } else {
                throw ValidationException::withMessages([
                    'email' => [__($status)],
                ]);
                return self::apiResponse(false, "Échec de l\'envoi du lien de réinitialisation");
           }
       } catch (ValidationException $e) {
            return self::apiResponse(false, "Erreur de validation");
       }
    }

    // RESET PASSWORD
    /**
     * REINITIALISER LE MOT DE PASSE
     */
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
            return self::apiResponse(true, "Mot de passe réinitialisé avec succès");
        } else {
            if ($status === Password::INVALID_TOKEN) {
                return self::apiResponse(false, "Token de réinitialisation invalide");
            } elseif ($status === Password::INVALID_USER) {
                return self::apiResponse(false, "Adresse e-mail non trouvée");
            } else {
                return self::apiResponse(false, "Échec de la réinitialisation du mot de passe");
            }
        }
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


    /**
     * MODIFIER UN EMPLOYE
     *
     * @bodyParam employee_id numeric required ID de l'empoyee
     * @bodyParam name string required Nom
     * @bodyParam password string required Mot de passe
     * @bodyParam password_confirmation string required Confirmation du mot de passe
     *
     */
    public function updateEmployee(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'employee_id' => ['required', 'numeric'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::find($data['employee_id']);
            $user->update($data);

            return self::apiResponse(true, "Employee mis à jour avec succès");
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de la mise à jour de l'employée");
        }
    }

    /**
     * NOMBRE DE PATIENTS TRAITES PAR EMPLOYE
     *
     * @bodyParam employee_id numeric required ID de l'empoyee
     */
    public function getPatientTreatByEmployee(Request $request)
    {
        try {
            $data = $request->validate([
                'employee_id' => ['required', 'numeric'],
            ]);
            $patients = Patient::where('user_id', $data['employee_id'])->with(['sells', 'user'])->get();;
            return self::apiResponse(true, "Patients de l'employé avec tous les infos", $patients);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de la récupération");
        }
    }
}
