<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Sell;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class NotificationController extends Controller
{
    /**
     * NOMBRE NOTIFICATIONS NON LUES
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::all();

        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');


        foreach ($patients as $patient) {

            $dateLivraison = strtotime($patient->date_save);
            // $dateLimite =  $dateLivraison + (60 * 60 * 24 * 365 * 2);
            $dateDay =  $dateLivraison + (60 * 1);
            $dateWeek =  $dateLivraison + (60 * 5);

            $dateDay = date('Y-m-d H:i:s', $dateDay);
            $dateWeek = date('Y-m-d H:i:s', $dateWeek);

            if (date("Y-m-d H:i:s", strtotime("now +1 hour")) >= $dateWeek) {

                $checkPatient = Notification::where('patient_id', $patient->id)->byType('week')->get();

                if ($checkPatient->isEmpty()) {
                    $notification = new Notification();
                    $notification->patient_id = $patient->id;
                    $notification->type = 'week';
                    $notification->save();


                    // ENVOI DE MESSAGE WHATSAPP
                    $twilio = new Client($twilioSid, $twilioToken);
                    // $twilio->messages
                    // ->create('whatsapp:+229'.$patient->phone_number, // to
                    //     array(
                    //     "from" => "whatsapp:+14155238886",
                    //     "body" => "Vous devez renouveler votre ordonnance dans 7 jours"
                    //     )
                    // );
                    $twilio->messages
              ->create("whatsapp:+22996135159", // to
                array(
                  "from" => "whatsapp:+14155238886",
                  "body" => "Your appointment is coming up on July 21 at 3PM"
                )
            );


                }
            }

            if (date("Y-m-d H:i:s", strtotime("now +1 hour")) >= $dateDay) {

                $checkPatient = Notification::where('patient_id', $patient->id)->byType('day')->get();

                if ($checkPatient->isEmpty()) {
                    $notification = new Notification();
                    $notification->patient_id = $patient->id;
                    $notification->type = 'day';
                    $notification->save();

                    // ENVOI DE MESSAGE WHATSAPP
                    $twilio = new Client($twilioSid, $twilioToken);
                    // $twilio->messages
                    // ->create('whatsapp:+229'.$patient->phone_number, // to
                    //     array(
                    //     "from" => "whatsapp:+14155238886",
                    //     "body" => "Vous devez renouveler votre ordonnance dans 24 heures"
                    //     )
                    // );
                    $twilio->messages
              ->create("whatsapp:+22996135159", // to
                array(
                  "from" => "whatsapp:+14155238886",
                  "body" => "Your appointment is coming up on July 21 at 3PM"
                )
            );

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
