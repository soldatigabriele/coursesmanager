<?php
namespace App\Helpers;

class Telegram
{
    // Chat ID
    // gabri = '31019486';
    // papa = '572616982';
    public static function alert($text, $disable_notification = 'false')
    {
        $client = new \GuzzleHttp\Client();

        $chat_id = app('config')->get('app.telegram.chat_id');
        
        $disable_notification = (env('APP_ENV') === 'testing')? 'true' : 'false';
        
        if($chat_id == null){
            return json_encode(['error' => 'no chat id selected']);
        }
        $url = env('TELEGRAM_URI') . env('TELEGRAM_TOKEN') . '/sendMessage?&disable_notification=' . $disable_notification . '&parse_mode=markdown&&chat_id=' . $chat_id . '&text=' . urlencode($text);
        $response = $client->request('GET', $url, ['Accept' => 'application/json']);
        return $response->getBody();
    }
}
