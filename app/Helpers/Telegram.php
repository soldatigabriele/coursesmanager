<?php
namespace App\Helpers;

use App\Course;
use App\Partecipant;


class Telegram{
	public static function alert(Partecipant $partecipant, Course $course)
	{
	    $client = new \GuzzleHttp\Client();

	    $chat_id = '31019486';
	    $text = 
	    	$partecipant->name.' '.$partecipant->surname.' - '.$partecipant->email.' '.$partecipant->phone.' si è iscritto al corso '.$course->long_id.' del '.$course->date;

	    $url = env('TELEGRAM_URI').env('TELEGRAM_TOKEN').'/sendMessage?chat_id='.$chat_id.'&text='.urlencode($text);

	    $response = $client->request('GET', $url, ['Accept' => 'application/json']);
	    $json = json_decode($response->getBody());
        return $json;
	}
}
