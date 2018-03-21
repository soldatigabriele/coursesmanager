<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SubscriptionsController extends Controller
{
    
public function index()
{
	return 'index ok';
}
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  integer $course_id
     * @param  integer $user_id
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
    	return 'ok';
    	$user = User::find($request->user_id);
    	$course = Course::find($request->course_id);
dd($user);
    	$user->attach($course);
		
		return 'ok';
    }
}
