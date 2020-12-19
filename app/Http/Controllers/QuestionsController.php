<?php

namespace App\Http\Controllers;

use App\Course;
use App\Question;
use Carbon\Carbon;
use App\Helpers\Logger;
use App\Jobs\TelegramAlert;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class QuestionsController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('end_date', '>', Carbon::today()->subDays(30))->orderByDesc('start_date')->get();
        return view('questions.index')->with(['courses' => $courses]);
    }

    public function create(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        // If "feed" is provided, the feedback fields are displayed
        // "qn" is the number of question inputs we want to render
        return view('questions.create')->with(['course_id' => $course->id, 'qn' => $request->qn, 'feed' => $request->feed]);
    }

    public function store(Request $request)
    {
          $messages = [
            'name.required' => 'Inserire un nome valido',
            'surname.required' => 'Inserire una cognome valido',
          ];
        $rules = [
            'name' => 'required|string',
            'surname' => 'required|string',
        ];

        if (env('REQUIRE_CAPTCHA') === 'yes') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data = ((array_merge($validation->getData(), ['errors' => $validation->errors()->getMessages()])));
            (new Logger)->log('0', 'Questions Error', $data, $request);
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }

        $data = collect($request->all());
        $questions = $data->filter(function($value, $key){
            return Str::contains($key, 'question');
        });
        $feedback = $data->filter(function($value, $key){
            return Str::contains($key, 'feedback');
        });
        $question = new Question([
            'name' => Str::title($request->name) . ' ' .  Str::title($request->surname),
            'questions' => $questions,
            'feedback' => $feedback,
        ]);

        $course = Course::findOrFail($request->courseId);
        $course->questions()->save($question);

        TelegramAlert::dispatch('nuova domanda da ' . $request->name . ' per il corso ' . $course->id, false, $request->toArray());

        return Response('Grazie per aver inviato le domande. <a href="https://laboa.org">clicca qui</a> per tornare al sito');
    }
}
