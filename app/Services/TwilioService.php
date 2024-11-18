<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendWhatsAppMessage($to, $message)
    {
        $twilioNumber = env('TWILIO_WHATSAPP_NUMBER');
        $to = "whatsapp:" . $to;

        $this->twilio->messages->create($to, [
            'from' => $twilioNumber,
            'body' => $message,
        ]);
    }
}
