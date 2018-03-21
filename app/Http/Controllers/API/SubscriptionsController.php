<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


class SubscriptionsController extends Controller
{
    

    public function getSubscriptions($user_id)
    {

        $user = User::find($user_id);
        $courses = $user->courses;
        return response()->json([$courses]);
    }


    public function subscribe(Request $request)
    {
        $user = User::find($request->user_id);

        if(Course::find($request->course_id)){
            $user->courses()->syncWithoutDetaching($request->course_id);
        }else{
            throw new HttpException(500, 'Course not found');
        }
        return $user->courses;
    }


    public function unsubscribe(Request $request)
    {
    	$user = User::find($request->user_id);
        $user->courses()->detach($request->course_id);
		return $user->courses;
    }
}
