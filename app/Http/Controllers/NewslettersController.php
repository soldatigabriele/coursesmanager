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
use App\Rules\Course as CourseRule;
use App\Rules\Region as RegionRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NewslettersController extends Controller
{

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
            'g-recaptcha-response.required' => 'Cliccare il box: "Non sono un robot"',
        ];
         $rules = [
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => 'required|email',
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
        $p = new Partecipant();
        $p->name = $request->name;
        $p->slug = str_random(30);
        $p->surname = $request->surname;
        $p->email = $request->email;
        $p->phone = $request->phone;

        array_forget($data, 'g-recaptcha-response');
        array_forget($data, 'name');
        array_forget($data, '_token');
        array_forget($data, 'subscribe');
        array_forget($data, 'surname');
        array_forget($data, 'email');
        array_forget($data, 'phone');
        array_forget($data, 'course_id');
        $p->data = json_encode($data);

        $p->meta = json_encode(['user_agent'=> request()->header('User-Agent'), 'ip' => request()->ip()], true);
        $p->save();
        $p = $p->fresh();
        $p->courses()->sync($request->course_id);

        // send and log the message
        // $response = Telegram::alert($p, Course::find($request->course_id));
        // (new Logger)->log($response);

        return redirect()->route('partecipant-show', ['slug' => $p->slug])->with('status', 'Iscrizione avvenuta con successo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Newsletter $n)
    {
        return view('newsletter.show')->with(['newsletter' => $n]);
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
