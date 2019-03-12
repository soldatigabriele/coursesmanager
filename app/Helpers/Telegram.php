<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Telegram
{

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send an alert to the Telegram channel
     * Chat ID
     * gabri = '31019486';
     * papa = '572616982';
     *
     * @param [type] $text
     * @param string $disable_notification
     * @return void
     */
    public function alert($text, $disable_notification = 'false')
    {
        $chat_id = app('config')->get('app.telegram.chat_id');
        $disable_notification = (env('APP_ENV') === 'testing') ? 'true' : 'false';

        if ($chat_id == null) {
            return json_encode(['error' => 'no chat id selected']);
        }
        $url = env('TELEGRAM_URI') . env('TELEGRAM_TOKEN') . '/sendMessage?&disable_notification=' . $disable_notification . '&parse_mode=markdown&&chat_id=' . $chat_id . '&text=' . urlencode($text);
        $response = $this->client->request('GET', $url, ['Accept' => 'application/json']);
        return $response->getBody();
    }
}
