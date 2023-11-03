<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * @authenticated
     * Liste des patients
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('patients.index', ['patients' => $patients]);
        try {
            $patients = Patient::all();
            return self::apiResponse(true, "Récupération de tous les patients", $patients);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Echec de la récupération de tous les patients");
        }
    }


    /**
     * Enregistrer un nouveau patient
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return redirect()->route('patients.index')->with('success', 'Patient ajouté avec succès.');
        try {

            $data = $request->validate([
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
            ]);
            $patient = Patient::create($data);
            return self::apiResponse(true, "Patient ajouté avec succès", $patient);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de l'ajout du patient");
        }
    }

    /**
     * Afficher un patient
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    // public function show(Patient $patient)
    // {
    //     // return view('patients.show', ['patient' => $patient]);
    //     try {
    //         return self::apiResponse(true, "Patient à voir", $patient);
    //     }catch( ValidationException ) {
    //         return self::apiResponse(false, "Echec de la recherche", $patient);
    //     }
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    // public function edit(Patient $patient)
    // {
    //     // return view('patients.edit', ['patient' => $patient]);
    //     try {
    //         return self::apiResponse(true, "Patient à mettre à jour", $patient);
    //     }catch( ValidationException ) {
    //         return self::apiResponse(false, "Echec de la recherche");
    //     }
    // }

    /**
     * Mettre à jour un patient
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        // return redirect()->route('patients.index')->with('success', 'Patient mis à jour avec succès.');

        try {
            $data = $request->validate([
                'last_name' => 'string',
                'first_name' => 'string',
                'email' => 'email|unique:patients,email,' . $patient->id,
                'phone_number' => 'string',
                'frame' => 'string',
                'reference' => 'string',
                'color' => 'string',
                'price' => 'numeric',
                'left_eye_vl_correction' => 'string',
                'left_eye_vp_correction' => 'string',
                'right_eye_vl_correction' => 'string',
                'right_eye_vp_correction' => 'string',
            ]);

            $patient->update($data);

            return self::apiResponse(true, "Patient mis à jour avec succès");
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de la mise à jour du patient");
        }
    }

    /**
     * Supprimer un patient
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        // return redirect()->route('patients.index')->with('success', 'Patient supprimé avec succès.');
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
