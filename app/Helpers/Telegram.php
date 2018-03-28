<?php
namespace App\Helpers;

use Auth;
use App\User;
use App\Course;
use App\Partecipant;


class Telegram{
	public static function alert(Partecipant $partecipant, Course $course)
	{
	    $client = new \GuzzleHttp\Client();

	    // mia
	    // $chat_id = '31019486';
	    // papa
	    // $chat_id = '572616982';
	    $chat_id = User::find($course->user_id)->telegram_chat_id;

	    $text = 
	    	$partecipant->name.' '.$partecipant->surname.' - '.$partecipant->email.' '.$partecipant->phone.' si è iscritto al corso '.$course->long_id.' del '.$course->date;

	    $url = env('TELEGRAM_URI').env('TELEGRAM_TOKEN').'/sendMessage?chat_id='.$chat_id.'&text='.urlencode($text);

	    $response = $client->request('GET', $url, ['Accept' => 'application/json']);
	    $json = $response->getBody();
	    return $json;
	}
}
