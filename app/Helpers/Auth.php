<?php
namespace App\Helpers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class FromToken{
	public static function getUser(Request $request)
	{
        $user = \App\User::where('api_token', $request->bearerToken())->first();
        if($user){
        	return $user;
        }
        return 'no user found';
	}
}
