<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    //

    public function sendWhatsAppMessage()
    {
        $twilioSid = env('TWILIO_SID');
        $twilioToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsAppNumber = env('TWILIO_WHATSAPP_NUMBER');
        $recipientNumber = 'whatsapp:+22996135159';
        $message = "Hello from Programming Experience";

        $twilio = new Client($twilioSid, $twilioToken);


        // $sid    = "AC8ded298ae0b59e071f90644ae9389946";
        // $token  = "[AuthToken]";
        // $twilio = new Client($sid, $token);



        try {
            // $twilio->messages->create(
            //     $recipientNumber,
            //     [
            //         "from" => 'whatsapp:'.$twilioWhatsAppNumber,
            //         "body" => $message,
            //     ]
            // );

            $message = $twilio->messages
              ->create("whatsapp:+22996135159", // to
                array(
                  "from" => "whatsapp:+14155238886",
                  "body" => "Your appointment is coming up on July 21 at 3PM"
                )
            );

            return response()->json(['message' => 'WhatsApp message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
