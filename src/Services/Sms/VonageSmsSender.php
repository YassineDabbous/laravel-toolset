<?php
namespace Ysn\SuperCore\Services\Sms;
 
use Illuminate\Support\Facades\Log;
use Ysn\SuperCore\Contracts\SmsSender;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\SMS\Message\SMS;

class VonageSmsSender implements SmsSender
{
    public Client $client;
    public function __construct()
    { 
        // Your Account SID and Auth Token from https://dashboard.nexmo.com/getting-started/sms => "9784fc27", "AqgIVT2cnHFYAScD"
        $sid = config('services.vonage.key');
        $token = config('services.vonage.secret');
        Log::info("vonage.key: $sid,         secret:$token"); 
        $basic  = new Basic($sid, $token);
        $this->client = new  Client($basic); 
    }


    public function send(string $msg, string $to, ?string $from=null) //, ?string $from
    {
        Log::info($to.': '.$msg);

        $response = $this->client->sms()->send(
            new SMS($to, 'MyApP', 'essahbi')
        );
        
        $message = $response->current();
        Log::info('vonage message status: '.$message->getStatus());
        
        // if ($message->getStatus() == 0) {
        //     echo "The message was sent successfully\n";
        // } else {
        //     echo "The message failed with status: " . $message->getStatus() . "\n";
        // }
    }
}
