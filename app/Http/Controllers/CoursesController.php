<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use Carbon\Carbon;
use App\Helpers\Logger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $courses = Course::where('user_id', Auth::user()->id)->where('end_date', '>', Carbon::today()->subDays(7))->orderBy('start_date')->paginate(10);
        return view('courses.index')->with(['courses' => $courses, 'header' => ['test', 'test2', 'test3']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
            $data = ((array_merge($validation->getData(), $validation->errors()->getMessages())));
            (new Logger)->log('0', 'Course Creation Error', json_encode($data), $request);
            return redirect(route('courses.create'))
                ->withErrors($validation)
                ->withInput();
        }

        (new Logger)->log('1', 'Course Creation Success', json_encode($request->all()), $request);

        $user_id = Auth::user()->id;
        $course = new Course;
        $course->date = $request->date;
        $course->limit = $request->limit;
        $course->description = $request->description;
        $start_date = Carbon::createFromFormat('d/m/yy', $request->start_date);
        $end_date = Carbon::createFromFormat('d/m/yy', $request->end_date);
        $course->start_date = $start_date;
        $course->end_date = $end_date;
        $course->user_id = $user_id;
        $course->save();
        $course->long_id = $course->fresh()->id . '-' . $request->long_id;
        $course->save();

        $message = 'Corso creato correttamente: ' . $course->description . ' - ' . $course->date . ' - ' . $course->long_id;

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
        return $course;
        // if($course->user_id == Man::Id($request)){
        //     return $course;
        // }
        // return response()->json(['error' => 'You cannot see this resource']);
    }

    /**
     * Show the form to update the specific resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        return view('courses.edit')->with(['course' => $course]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Course $course, Request $request)
    {

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
            $data = ((array_merge($validation->getData(), $validation->errors()->getMessages())));
            (new Logger)->log('0', 'Course Update Error', json_encode($data), $request);
            return redirect(route('courses.edit', $course->id))
                ->withErrors($validation);
        }

        (new Logger)->log('1', 'Course Update Success', json_encode($request->all()), $request);

        $course->fill($request->all());

        $start_date = Carbon::createFromFormat('d/m/yy', $request->start_date);
        $end_date = Carbon::createFromFormat('d/m/yy', $request->end_date);

        $course->start_date = $start_date;
        $course->end_date = $end_date;
        $course->save();

        return back()->with('edited', 'Corso ' . $course->long_id . ' aggiornato correttamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('deleted', 'Corso ' . $course->long_id . ' eliminato correttamente');
    }

    /**
     * Show the table to be exported
     *
     * @param  \App\Course $course
     * @return \Illuminate\Http\Response
     */
    public function export(Course $course)
    {
        return view('courses.export')->with(['course' => $course]);
    }
}
