<?php
namespace App\Helpers;

use Illuminate\Http\Request;

class Manager{
	public static function Id(Request $request)
	{
        $manager = \App\Manager::where('api_token', $request->bearerToken())->first();
        if($manager){
        	return $manager->id;
        }
        return 'no manager found';

	}
}