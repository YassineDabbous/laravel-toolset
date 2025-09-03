<?php
namespace Ysn\SuperCore\Services\Sms;
 
use Illuminate\Support\Facades\Log;
use Ysn\SuperCore\Contracts\SmsSender;
use Twilio\Rest\Client;

class TwilioSmsSender implements SmsSender
{
    public Client $client;
    public function __construct()
    { 
        // Your Account SID and Auth Token from twilio.com/console
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        // Log::info("sid: $sid,         token:$token");
        $this->client = new Client($sid, $token);
        /* 
         {
                "onesignal_app_id": "f841a015-b3eb-4c08-bd09-80406f799f75",
                "onesignal_rest_api": "NWNkZDRiMTMtMjY3ZS00MGE0LTg5ZjYtMzA0NTNkM2U0MTJk",
                "sid": "ACb3f503b637f2cb66218eae055dda8144",
                "token": "292cb4739d355c4db288d579e1e291c6"
            }

        EJAR :
        $sid = 'ACece67303cc6c42c833ba79ef2a9dec43';
        $token = '9e018ce8da618f2bc35f0243d4755ec4';
        $from = '+18312736732'; 
        
        */

    }


    public function send(string $msg, string $to, ?string $from=null) //, ?string $from
    {
        // Log::info("from ".config('services.twilio.number'));
        Log::info($to.': '.$msg);
        $this->client->messages->create(
            // the number you'd like to send the message to
            $to, // '+15558675309',
            [
                // A Twilio phone number you purchased at twilio.com/console
                'from' => config('services.twilio.number'), //$from, //'+15017250604',
                // the body of the text message you'd like to send
                'body' => $msg, //'Hey Jenny! Good luck on the bar exam!'
            ]
        );
        
    }
}
