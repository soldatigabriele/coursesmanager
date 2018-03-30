<?php
namespace App\Helpers;

use Auth;
use App\User;
use App\Course;
use App\Partecipant;
use App\Helpers\Logger;


class Telegram{

	public static function alert($text, $disable_notification = 'false')
	{
	    $client = new \GuzzleHttp\Client();
	    // mia
	    $chat_id = '31019486';
	    if(env('APP_ENV') === 'testing'){
	    	$chat_id = '31019486';
	    	$disable_notification = true;
	    }
	    if(env('APP_ENV') === 'production')
	    {
// replace with db value
	    	$disable_notification = 'false';
	    	// use the db value
	    	// $chat_id = User::find($course->user_id)->telegram_chat_id;
    		// papa
	    	$chat_id = '572616982';
	    }
	    $url = env('TELEGRAM_URI').env('TELEGRAM_TOKEN').'/sendMessage?&disable_notification='.$disable_notification.'&parse_mode=markdown&&chat_id='.$chat_id.'&text='.urlencode($text);
	    $response = $client->request('GET', $url, ['Accept' => 'application/json']);
	    $json = $response->getBody();

	    return $json;
	}
}
