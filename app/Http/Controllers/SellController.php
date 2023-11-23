<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SellController extends Controller
{
    /**
     * LISTES DES VENTES
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sells = Sell::all();
            return self::apiResponse(true, "Récupération de tous les ventes", $sells);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Echec de la récupération de tous les ventes");
        }
    }

    /**
     * ENREGISTRER UNE VENTE
     *
     * @bodyParam patient_id number required ID du patient
     * @bodyParam verre_type string required Type de verre
     * @bodyParam montant numeric required Montant
     * @bodyParam acompte numeric Acompte
     * @bodyParam solde numeric Solde
     * @bodyParam date_livraison date required Date de livraison
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'patient_id' => 'required|numeric',
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
     * METTRE A JOUR UNE VENTE
     *
     * @bodyParam patient_id numeric ID du patient
     * @bodyParam verre_type string Type de verre
     * @bodyParam montant numeric Montant
     * @bodyParam acompte numeric Acompte
     * @bodyParam solde numeric Solde
     * @bodyParam date_livraison date Date de livraison
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sell $sell)
    {
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
     * SUPPRIMER UNE VENTE
     *
     * @param  \App\Models\Sell  $sell
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sell $sell)
    {
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
