<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Patient;
use App\Models\Sell;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * NOMBRE NOTIFICATIONS NON LUES
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sells = Sell::select('patient_id', 'date_livraison')->get();

        foreach ($sells as $sell) {
            $patient = Notification::where('patient_id', $sell->patient_id)->first();

            if (!$patient) {
                $dateLivraison = strtotime($sell->date_livraison);
                // $dateLimite = strtotime("+2 years", $dateLivraison);
                $dateLimite = date('Y-m-d', $dateLivraison);

                if (date('Y-m-d') >= $dateLimite) {
                    $notification = new Notification();
                    $notification->patient_id = $sell->patient_id;
                    $notification->save();
                }
            }
        }

        $count = Notification::where('status', 0)->count();

        return self::apiResponse(true, 'Nombre de notifications non lues', $count, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * METTRE A JOUR LES NOTIFICATIONS
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notifications = Notification::where('status', 0)->get();
        foreach ($notifications as $notification) {
            $notification->status = 1;
            $notification->save();
        }
        $notifications = Notification::with(['patient'])->get();

        return self::apiResponse(true, "Notifications mis à jours", $notifications, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    // public function show(Notification $notification)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    // public function edit(Notification $notification)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, Notification $notification)
    // {
    //     //
    // }

    /**
     * SUPRIMER UNE NOTIFICATION
     *
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {

            $notification->delete();
            return self::apiResponse(true, "Notification supprimée avec succès", 200);
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
