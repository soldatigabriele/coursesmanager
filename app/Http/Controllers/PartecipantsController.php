<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Course;
use App\Region;
use App\Partecipant;
use App\Helpers\Logger;
use App\Helpers\Telegram;
use App\Helpers\FromToken;
use Illuminate\Http\Request;
use App\Helpers\CollectionHelpers;
use App\Rules\Course as CourseRule;
use App\Rules\Region as RegionRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class PartecipantsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $myPartecipants = Partecipant::select('partecipants.*')
        // ->join('course_partecipant', 'course_partecipant.partecipant_id', '=', 'partecipants.id')
        // ->join('courses', 'courses.id', '=', 'course_partecipant.course_id')
        // ->where('courses.user_id', Auth::user()->id)
        // ->paginate(10);

        $myPartecipants = User::partecipants(); 
        $myPartecipants = (new CollectionHelpers())->paginate($myPartecipants);

        return view('partecipants.index')->with(['partecipants'=> $myPartecipants, 'emails' => Partecipant::select('email')->get(), 'regions'=>Region::all()]);
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
        return view('forms.create')->with(['regions'=> Region::all(), 'courses'=> Course::all()]);
    }


    /**
     * Show the scheda 1 form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scheda1()
    {
        // return the course creation form 
        return view('forms.scheda1')->with(['regions'=> Region::all(), 'courses'=> Course::all()]);
    }


    /**
     * Show the scheda 2 form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scheda2()
    {
        // return the course creation form 
        return view('forms.scheda2')->with(['regions'=> Region::all(), 'courses'=> Course::all()]);
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
            'phone.required' => 'Inserire un numero di telefono valido',
            'job.required' => 'Inserire una professione valida',
            'city.required' => 'Inserire la propria provenienza',
            'email.required' => 'Inserire un indirizzo email',
            'email_again.same' => 'Le email non coincidono',
            'email.email' => 'Inserire un indirizzo email valido',
            'g-recaptcha-response.required' => 'Cliccare il box: "Non sono un robot"',
        ];
        $rules = [
            'name' => 'required|string',
            'surname' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email',
            'email_again' => 'same:email',
            'job' => 'required',
            'city' => 'required',
            'region_id' => new RegionRule,
            'course_id' => new CourseRule,
        ];

        if(env('REQUIRE_CAPTCHA') === 'yes' ){
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data = ((array_merge($validation->getData(), $validation->errors()->getMessages())));

            (new Logger)->log('0', 'Partecipant Subscription Error', json_encode($data), $request);
            return redirect()->back()
                        ->withErrors($validation)
                        ->withInput();
        }
        (new Logger)->log('1', 'Partecipant Subscription Success', json_encode($request->all()), $request);
        $data = $request->all();
        $p = new Partecipant();
        $p->name = $request->name;
        $p->slug = str_random(30);
        $p->surname = $request->surname;
        $p->region_id = $request->region_id;
        $p->email = $request->email;
        $p->phone = $request->phone;

        array_forget($data, 'g-recaptcha-response');
        array_forget($data, 'name');
        array_forget($data, '_token');
        array_forget($data, 'subscribe');
        array_forget($data, 'surname');
        array_forget($data, 'email');
        array_forget($data, 'email_again');
        array_forget($data, 'phone');
        array_forget($data, 'course_id');
        $p->data = json_encode($data);

        $p->meta = json_encode(['user_agent'=> request()->header('User-Agent'), 'ip' => request()->ip()], true);
        $p->save();
        $p = $p->fresh();
        $p->courses()->sync($request->course_id);

        if(env('APP_ENV') !== 'testing' ){
            // send and log the message
            $response = Telegram::alert($p, Course::find($request->course_id));

            (new Logger)->log('2', 'Telegram Response', $response, $request);
        }

        return redirect()->route('partecipant-show', ['slug' => $p->slug])->with('status', 'Iscrizione avvenuta con successo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $p = Partecipant::where('slug', $slug)->first();
        if($p){
            $courses = $p->courses;
            return view('partecipants.show')->with(['partecipant' => $p]);
        }
        return 'no user found';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partecipant $partecipant)
    {
        $partecipant->fill($request->all());
        $partecipant->save();
        return $partecipant;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partecipant $partecipant)
    {
        $partecipant->delete();
        return $partecipant;
    }
}
