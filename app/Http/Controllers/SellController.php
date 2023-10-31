<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class SellController extends Controller
{
    /**
     * Liste des ventes
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('sells.index', ['sells' => $sells]);
        try {
            $sells = Sell::all();
            return self::apiResponse(true, "Récupération de tous les ventes", $sells);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Echec de la récupération de tous les ventes");
        }
    }

    /**
     * Enregistrer une nouvelle vente
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return redirect()->route('sells.index')->with('success', 'Vente ajoutée avec succès.');
        try {

            $data = $request->validate([
                'patient_id' => 'required|number',
                'verre_type' => 'required|string',
                'montant' => 'required|numeric',
                'acompte' => 'numeric',
                'solde' => 'numeric',
                'date_livraison' => 'required|date',
            ]);
            $sell = Sell::create($data);

            return self::apiResponse(true, "Vente ajouté avec succès", $sell);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de l'ajout de la vente");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    // public function show(Sell $sell)
    // {
    //     // return view('sells.show', ['sell' => $sell]);
    //     try {
    //         return self::apiResponse(true, "Vente à voir", $sell);
    //     }catch( ValidationException ) {
    //         return self::apiResponse(false, "Echec de la recherche");
    //     }
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    // public function edit(Sell $sell)
    // {
    //     // return view('sells.edit', ['sell' => $sell]);
    //     try {
    //         return self::apiResponse(true, "Patient à mettre à jour", $sell);
    //     }catch( ValidationException ) {
    //         return self::apiResponse(false, "Echec de la recherche");
    //     }
    // }

    /**
     * Mettre à jour une vente
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sell $sell)
    {
        // return redirect()->route('sells.index')->with('success', 'Vente mise à jour avec succès.');
        try {
            $data = $request->validate([
                'patient_id' => 'number',
                'verre_type' => 'string',
                'montant' => 'numeric',
                'acompte' => 'numeric',
                'solde' => 'numeric',
                'date_livraison' => 'date',
            ]);
            $sell->update($data);
            return self::apiResponse(true, "Patient mis à jour avec succès");
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de la mise à jour du patient");
        }
    }

    /**
     * Supprimer une vente
     *
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sell $sell)
    {
        // return redirect()->route('sells.index')->with('success', 'Vente supprimée avec succès.');
        try {
            $sell->delete();
            return self::apiResponse(true, "Vente supprimé avec succès");
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
