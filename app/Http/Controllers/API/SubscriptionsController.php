<?php

namespace App\Http\Controllers\API;

use App\Course;
use App\Http\Controllers\Controller;
use App\Partecipant;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SubscriptionsController extends Controller
{

    public function getSubscriptions($partecipant_id)
    {

        $partecipant = Partecipant::find($partecipant_id);
        $courses = $partecipant->courses;
        return response()->json([$courses]);
    }

    public function subscribe(Request $request)
    {
        $partecipant = Partecipant::find($request->partecipant_id);

        if (Course::find($request->course_id)) {
            $partecipant->courses()->syncWithoutDetaching($request->course_id);
        } else {
            throw new HttpException(500, 'Course not found');
        }
        return $partecipant->courses;
    }

    public function unsubscribe(Request $request)
    {
        $partecipant = Partecipant::find($request->partecipant_id);
        $partecipant->courses()->detach($request->course_id);
        return $partecipant->courses;
    }
}
