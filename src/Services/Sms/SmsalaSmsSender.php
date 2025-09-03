<?php
namespace Ysn\SuperCore\Services\Sms;
 
use Illuminate\Support\Facades\Log;
use Ysn\SuperCore\Contracts\SmsSender;
use Illuminate\Support\Facades\Http;

class SmsalaSmsSender implements SmsSender
{
    public function __construct()
    { 
    }


    public function send(string $msg, string $to, ?string $from=null) //, ?string $from
    {
        Log::info('SMSala message to: '.$to.' => '.$msg);

        // $response = Http::post('https://api.smsala.com/api/SendSMS', [
        //     // api_id=API1161968148544&api_password=r4uPMZSeuF&sms_type=P&encoding=T&sender_id=KPG&phonenumber=97450164060&=test
        //     'api_id' => 'API1161968148544',
        //     'api_password' => 'r4uPMZSeuF',
        //     'sms_type' => 'P',
        //     'encoding' => 'T',
        //     'sender_id' => 'KPG',
        //     'phonenumber' => $to,
        //     'textmessage' => $msg,
        // ]);
        
        // Log::info('SMSala message status: '.$response->status());
    }
}
