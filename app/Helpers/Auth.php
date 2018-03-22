<?php
namespace App\Helpers;

use Illuminate\Http\Request;

class Auth{
	public static function user(Request $request)
	{
        $user = \App\User::where('api_token', $request->bearerToken())->first();
        if($user){
        	return $user;
        }
        return 'no user found';
	}
}
