<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     *
     * LISTE DES PATIENTS
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $patients = Patient::all();
            return self::apiResponse(true, "Récupération de tous les patients", $patients);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Echec de la récupération de tous les patients");
        }
    }

    /**
     *
     * LISTE DES PATIENTS AVEC LEURS INFOS
     *
     * @return \Illuminate\Http\Response
     */
    public function getPatientsWithAllsInfos()
    {
        try {
            $patients = Patient::with(['sells', 'user'])->get();
            return self::apiResponse(true, "Récupération de tous les patients avec leurs infos", $patients);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Echec de la récupération de tous les patients");
        }
    }


    /**
     * ENREGISTRER UN PATIENT
     *
     * @bodyParam user_id numeric required ID Employé
     * @bodyParam last_name string required Nom
     * @bodyParam first_name string required Prenom
     * @bodyParam email string required Email
     * @bodyParam phone_number string required Téléphone
     * @bodyParam frame string required Monture
     * @bodyParam reference string required Reference
     * @bodyParam color string required Adresse Couleur
     * @bodyParam price string required Prix
     * @bodyParam left_eye_vl_correction string required Correction Oeil Gauche VL
     * @bodyParam left_eye_vp_correction string required Correction Oeil Gauche VP
     * @bodyParam right_eye_vl_correction string required Correction Oeil Droit VL
     * @bodyParam right_eye_vp_correction string required Correction Oeil Droit
     * @bodyParam date_save datetime required Date and time
     * @bodyParam treatment string Traitement
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'user_id' => 'required|numeric',
                'last_name' => 'required|string',
                'first_name' => 'required|string',
                'email' => 'required|email|unique:patients,email',
                'phone_number' => 'required|string',
                'frame' => 'required|string',
                'reference' => 'required|string',
                'color' => 'required|string',
                'price' => 'required|numeric',
                'left_eye_vl_correction' => 'required|string',
                'left_eye_vp_correction' => 'required|string',
                'right_eye_vl_correction' => 'required|string',
                'right_eye_vp_correction' => 'required|string',
                'date_save' => 'required',
                'treatment' => 'required|string'
            ]);
            $patient = Patient::create($data);
            return self::apiResponse(true, "Patient ajouté avec succès", $patient);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de l'ajout du patient");
        }
    }


    /**
     * METTRE A JOUR UN PATIENT
     *
     * @bodyParam user_id numeric required ID Employé
     * @bodyParam last_name string Nom
     * @bodyParam first_name string Prenom
     * @bodyParam email string Email
     * @bodyParam phone_number string Téléphone
     * @bodyParam frame string Monture
     * @bodyParam reference string Reference
     * @bodyParam color string Adresse Couleur
     * @bodyParam price string Prix
     * @bodyParam left_eye_vl_correction string Correction Oeil Gauche VL
     * @bodyParam left_eye_vp_correction string Correction Oeil Gauche VP
     * @bodyParam right_eye_vl_correction string Correction Oeil Droit VL
     * @bodyParam right_eye_vp_correction string Correction Oeil Droit VP
     * @bodyParam date_save datetime required Date and time
     * @bodyParam treatment string Traitement
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required|string',
                'last_name' => 'string',
                'first_name' => 'string',
                'email' => 'email',
                'phone_number' => 'string',
                'frame' => 'string',
                'reference' => 'string',
                'color' => 'string',
                'price' => 'numeric',
                'left_eye_vl_correction' => 'string',
                'left_eye_vp_correction' => 'string',
                'right_eye_vl_correction' => 'string',
                'right_eye_vp_correction' => 'string',
                'date_save' => 'date',
                'treatment' => 'string'
            ]);

            $patient->update($data);

            return self::apiResponse(true, "Patient mis à jour avec succès", $patient);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de la mise à jour du patient", $patient);
        }
    }

    /**
     * SUPPRIMER UN PATIENT
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        try {
            $patient->delete();
            return self::apiResponse(true, "Patient supprimé avec succès");
        }catch( ValidationException ) {
            return self::apiResponse(false, "Echec de la suppression");
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
}
