<?php
namespace App\Helpers;

use App\Course;
use App\Partecipant;
use App\ApplicationLog;


class Logger{


	/*
	*
	* @params json $json_data
	*/
	public function log($status, $description, $json_data)
	{
		$log = new ApplicationLog;
		$log->status = $status;
		$log->description= $description;
		$log->value= $json_data;
		$log->save();
		return $this;
	}
}
