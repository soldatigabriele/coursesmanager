<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id  = Auth::user()->id;
        dd($user_id);
        $courses = Course::where('user_id', $user_id)->get();
        if($courses->count()){
            return $courses;
        }
        return response()->json(['error' => 'No course found']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Man::Id($request);
        $course = new Course;
        $course->long_id = $request->long_id;
        $course->date = $request->date;
        $course->limit = $request->limit;
        $course->description = $request->description;
        $course->user_id = $user_id;
        $course->save();
        return $course;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course, Request $request)
    {
        ($request->bearerToken());
        if($course->user_id == Man::Id($request)){
            return $course;
        }
        return response()->json(['error' => 'You cannot see this resource']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        $course->fill($request->all());
        return $course;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return $course;
    }
}
