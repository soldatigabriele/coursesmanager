<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Course;
use App\Region;
use Carbon\Carbon;
use App\Newsletter;
use App\Partecipant;
use App\Helpers\Logger;
use App\Helpers\Telegram;
use App\Helpers\FromToken;
use App\Jobs\TelegramAlert;
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
    public function index(Request $request)
    {

        // $myPartecipants = Partecipant::select('partecipants.*')
        // ->join('course_partecipant', 'course_partecipant.partecipant_id', '=', 'partecipants.id')
        // ->join('courses', 'courses.id', '=', 'course_partecipant.course_id')
        // ->where('courses.user_id', Auth::user()->id)
        // ->paginate(10);

        //unire partecipanti e newsletter
        // selezionando distinct mail 

        
        // if(isset($request->find)){
        //     $parts = User::partecipants();
        //         // ->where('email', 'like', '%' . $request->email . '%');
        //     $parts->filter(function ($item) use ($productName) {
        //         return false !== preg_match($item->email, $request->email);
        //     )
        //     dd($parts);
        //     $news = Newsletter::all()
        //         ->where('email', 'like', '%' . $request->email . '%')->all();
        //     $all = array_merge($parts, $news);
        //     $emails = [];
        // }else{

        $parts = User::partecipants();
        $news = Newsletter::all(); 

        $region_id = (isset($request->region_id))? $request->region_id: null;
        if(isset($request->find)){
            $region_id = $request->region_id;
            $parts = $parts->filter(function($item, $value) use ($region_id){
                return $item->region['id'] == $region_id;
            });
            $news = Newsletter::where('region_id', $region_id)->get();
        }

        $all = $parts->merge($news);
        // get the emails
        $emails = $parts->pluck('email');
        $news_emails = $news->pluck('email');
        $emails = $emails->merge($news_emails);
        $regions = Region::all();
        // }
        
        $all = (new CollectionHelpers())->paginate($all);


        return view('partecipants.index')->with(['regions'=>$regions, 'region_id'=>$region_id, 'partecipants'=> $all, 'emails' => $emails, 'regions'=>Region::all()]);
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
        return view('forms.create')->with(['regions'=> Region::all(), 'courses'=> Course::where('end_date', '>', Carbon::today())->get()]);
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
        return view('forms.scheda1')->with(['regions'=> Region::all(), 'courses'=> Course::where('end_date', '>', Carbon::today())->get()]);
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
        return view('forms.scheda2')->with(['regions'=> Region::all(), 'courses'=> Course::where('end_date', '>', Carbon::today())->get()]);
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
            'course_id' => [new CourseRule, 'required'],
        ];


        if (env('REQUIRE_CAPTCHA') === 'yes') {
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
        array_forget($data, 'disableNotification');
        array_forget($data, 'testTelegramMessages');
        array_forget($data, 'name');
        array_forget($data, '_token');
        array_forget($data, 'subscribe');
        array_forget($data, 'surname');
        array_forget($data, 'email');
        array_forget($data, 'email_again');
        array_forget($data, 'phone');
        array_forget($data, 'course_id');
        $p->data = json_encode(array_map('ucfirst', (array_map('strtolower', $data))));

        $p->meta = json_encode(['user_agent'=> request()->header('User-Agent'), 'ip' => request()->ip()], true);
        $p->save();
        $p = $p->fresh();
        $p->courses()->sync($request->course_id);

        // send and log the message
        $c = Course::find($request->course_id);
        $url = url(route('courses.index').'?course_id='. $c->id.'&partecipant_id='. $p->fresh()->id);
        $text = '*'.$p->name.' '.$p->surname.'* - *'.$p->email.'* *'.$p->phone.'* si è iscritto al corso *'.$c->long_id.'* del '.$c->date.' [Vai alla scheda]('.$url.')';
        // send and log the message
        $disableNotification = ($request->disableNotification)?? false;
        TelegramAlert::dispatch($text, $disableNotification, $request->toArray());
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
        abort(404);
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
