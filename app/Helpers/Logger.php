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
	public function log($status, $type, $json_data)
	{
		$log = new ApplicationLog;
		$log->value= $json_data;
		$log->status= $status;
		$log->type = $type;
		$log->save();
		return $this;
	}
}
