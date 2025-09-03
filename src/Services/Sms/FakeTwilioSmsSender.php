<?php
namespace Ysn\SuperCore\Services\Sms;
 
use Illuminate\Support\Facades\Log;
use Ysn\SuperCore\Contracts\SmsSender;
use Twilio\Rest\Client;

class FakeTwilioSmsSender implements SmsSender
{
    public Client $client;
    public function __construct()
    { 
        $sid = 'ACece67303cc6c42c833ba79ef2a9dec43';
        $token = '9e018ce8da618f2bc35f0243d4755ec4';
        $this->client = new Client($sid, $token);
    }


    public function send(string $msg, string $to, ?string $from=null) 
    {
        $from = '+18312736732'; 
        $this->client->messages->create(
            $to,
            [
                'from' => $from ,
                'body' => $msg, 
            ]
        );
        
        Log::info($to.': '.$msg);
    }
}
