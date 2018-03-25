<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CoursesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = Course::where('user_id', Auth::user()->id)->orderByDesc('created_at')->paginate(10);
        return view('courses.index')->with(['courses' => $courses, 'header'=>['test', 'test2', 'test3']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
// TODO validate input

        $messages = [
            'long_id.required' => 'Inserire un codice corso valido',
            'date.required' => 'Inserire una data',
            'description.required' => 'Inserire il nome corso',
        ];
         $rules = [
            'date' => 'required',
            'description' => 'required',
            'long_id' => 'required',
        ];
    
        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return redirect(route('course-create'))
                        ->withErrors($validation)
                        ->withInput();
        }
     
        $user_id = Auth::user()->id;
        $course = new Course;
        $course->date = $request->date;
        $course->limit = $request->limit;
        $course->description = $request->description;
        $course->user_id = $user_id;
        $course->save();
        $course->long_id = $course->fresh()->id.'-'.$request->long_id;
        $course->save();

        $message = 'Corso creato correttamente: '.$course->description.' - '.$course->date.' - '.$course->long_id;

        return redirect('/')->with('status', $message);

        

    }

    /**
     * Show the create page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return the course creation form 
        return view('courses.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course, Request $request)
    {
        // if($course->user_id == Man::Id($request)){
        //     return $course;
        // }
        // return response()->json(['error' => 'You cannot see this resource']);
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
        return back()->with('deleted', 'Corso '.$course->long_id.' eliminato correttamente');
    }
}
