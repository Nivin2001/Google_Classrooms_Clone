<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class HadaraSms
{
   protected $baseUrl = 'http://smsservice.hadara.ps:4545/SMS.ashx/bulkservice/sessionvalue';
   protected $key;
  

    public function __construct($key) {
        //الصح انه ما نخلي القيم تبعتنا مقيدة يمكان محدد
        // $this->key = config('services.hadara.key');
    $this->key = $key;
   }

   public function send($to , $message)
   {
    $response = Http::baseUrl($this->baseUrl)
    ->withHeaders([
        'x-api-key' => $this->key,
        // 'Authorization' => 'Bearer'.$this->key,
        // 'Authorization' => 'Basic'.$this->key,
        ])
    ->withToken($this->key)
    ->post('sendmessage',[
        'apikey' => $this->key,
        'to' => $to,
        'msg' => $message,
    ]);
    // $json = $response->json();//بيحولخ ل json
    // $body = $response->body();//بيرجع ال body خام
    dd($response->body());
   }

}