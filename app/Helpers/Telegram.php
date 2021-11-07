<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class Telegram
{

    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client;
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
    public function alert(string $text, $disable_notification = 'false')
    {
        $chatId = app('config')->get('app.telegram.chat_id');
        $disable_notification = (env('APP_ENV') === 'testing') ? 'true' : 'false';

        if ($chatId == null) {
            return json_encode(['error' => 'no chat id selected']);
        }
        $uri = app('config')->get('app.telegram.uri');
        $token = app('config')->get('app.telegram.token');
        // https://api.telegram.org/bot{token}/sendMessage?&disable_notification=false&parse_mode=markdown&&chat_id={chat_id}&text=message
        $url = $uri . 'bot' . $token . '/sendMessage?&disable_notification=' . $disable_notification . '&parse_mode=markdown&&chat_id=' . $chatId . '&text=' . urlencode($text);
        $response = $this->client->request('GET', $url, ['Accept' => 'application/json']);
        return $response->getBody();
    }
}
