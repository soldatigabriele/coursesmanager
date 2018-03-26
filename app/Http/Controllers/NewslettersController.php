<?php

namespace App\Http\Controllers;

use App\Course;
use App\Region;
use App\Newsletter;
use App\Partecipant;
use App\Helpers\Logger;
use App\Helpers\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rules\Region as RuleRegion;
use App\Rules\Course as CourseRule;
use App\Rules\Region as RegionRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewslettersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => [
                'create', 'store', 'show'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletter = DB::table('newsletters')->orderBy('created_at', 'desc')->paginate(10);

        return view('newsletters.index')->with(['newsletters' => $newsletter, 'regions' => Region::all(), 'emails' => Newsletter::select('email')->distinct()->get()]);
    }


    /**
     * Show the create page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newsletters.create')->with(['regions'=> Region::all()]);

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
            'name.required' => 'Inserire un nome valido',
            'surname.required' => 'Inserire una cognome valido',
            'email.required' => 'Inserire un indirizzo email',
            'email.email' => 'Inserire un indirizzo email valido',
            'email_again.same' => 'Le email non coincidono',
            'region_id.required' => 'Inserire una regione valida',
            'g-recaptcha-response.required' => 'Cliccare il box: "Non sono un robot"',
        ];
         $rules = [
            'name' => 'required|string',
            'surname' => 'required|string',
            'region_id' => new RuleRegion,
            'email' => 'required|email',
            'email_again' => 'same:email',
        ];
        if(env('REQUIRE_CAPTCHA') === 'yes' ){
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validation = Validator::make($request->all(), $rules, $messages);
        
        if ($validation->fails()) {
            $data = ((array_merge($validation->getData(), $validation->errors()->getMessages())));

            (new Logger)->log('0', 'Newsletter Subscription Error', json_encode($data));
            return redirect()->back()
                        ->withErrors($validation)
                        ->withInput();
        }

        (new Logger)->log('1', 'Newsletter Subscription Success', json_encode($request->all()));
        
        $data = $request->all();
        $newsletter = new Newsletter();
        $newsletter->name = $request->name;
        $newsletter->surname = $request->surname;
        $newsletter->region_id = $request->region_id;
        $newsletter->email = $request->email;
        $newsletter->active = 1;
        $newsletter->meta = json_encode(['user_agent'=> request()->header('User-Agent'), 'ip' => request()->ip()], true);
        $newsletter->save();

        // if(env('APP_ENV') !== 'testing' ){
        //     // send and log the message
        //     $response = Telegram::alert($p, Course::find($request->course_id));
        //     (new Logger)->log('2', 'Telegram Response', $response, $request);
        // }

        return redirect()->route('newsletter-show', $newsletter)->with('status', 'Iscrizione alla newsletter avvenuta con successo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($newsletter)
    {
        $n = Newsletter::find($newsletter);
        return view('newsletters.show')->with(['newsletter' => $n]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Newsletter $n)
    {
        $n->active = 0;
        $n->delete();
        return $n;
    }
}
