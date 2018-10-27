<?php

namespace App\Http\Controllers;

use App\User;
use App\Coupon;
use App\Course;
use App\Region;
use Carbon\Carbon;
use App\Partecipant;
use App\Helpers\Logger;
use App\Jobs\TelegramAlert;
use Illuminate\Http\Request;
use App\Helpers\CollectionHelpers;
use App\Rules\Course as CourseRule;
use App\Rules\Region as RegionRule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PartecipantsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get all the user's courses partecipants
        $parts = User::partecipants();
        $region_id = (isset($request->region_id)) ? $request->region_id : null;
        if ($region_id) {
            $parts = Partecipant::where('region_id', $region_id)->get();
        }
        $emails = $parts->pluck('email');
        $regions = Region::all();
        $all = (new CollectionHelpers())->paginate($parts);
        return view('partecipants.index')->with(['regions' => $regions, 'region_id' => $region_id, 'partecipants' => $all, 'emails' => $emails, 'regions' => Region::all()]);
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
        return view('forms.create')->with(['regions' => Region::all(), 'courses' => Course::where('end_date', '>', Carbon::today())->get()]);
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
        return view('forms.scheda1')->with(['regions' => Region::all(), 'courses' => Course::where('end_date', '>', Carbon::today())->get()]);
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
        return view('forms.scheda2')->with(['regions' => Region::all(), 'courses' => Course::where('end_date', '>', Carbon::today())->get()]);
    }

    /**
     * Show the scheda 3 form.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function scheda3()
    {
        // return the course creation form
        return view('forms.scheda3')->with(['regions' => Region::all(), 'courses' => Course::where('end_date', '>', Carbon::today())->get()]);
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

        $partecipant = new Partecipant();
        $partecipant->name = $request->name;
        $partecipant->slug = str_random(30);
        $partecipant->surname = $request->surname;
        $partecipant->region_id = $request->region_id;
        $partecipant->email = $request->email;
        $partecipant->phone = $request->phone;

        // Unset all the unused variables
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

        // format the data
        $tempData = array_map('ucfirst', (array_map('strtolower', $data)));

        // Check if the user has a valid coupon
        if ($coupon = session()->get('coupon')) {
            // Double check coupon's validity and increase the counter
            if ($c = Coupon::where('value', $coupon)->first()) {
                $c->increment('usages');
                // Set the coupon in the extra data
                $tempData['coupon'] = $coupon;
            }
        }

        $partecipant->data = json_encode($tempData);

        $partecipant->meta = json_encode(['user_agent' => request()->header('User-Agent'), 'ip' => request()->ip()], true);
        $partecipant->save();
        $partecipant = $partecipant->fresh();
        
        // Get the course
        $course_id = $request->course_id;
        $partecipant->courses()->sync($course_id);

        // send and log the message
        $c = Course::find($course_id);
        $url = url(route('courses.index') . '?course_id=' . $c->id . '&partecipant_id=' . $partecipant->fresh()->id);
        $text = '*' . $partecipant->name . ' ' . $partecipant->surname . '* - *' . $partecipant->email . '* *' . $partecipant->phone . '* si è iscritto al corso *' . $c->long_id . '* del ' . $c->date . ' [Vai alla scheda](' . $url . ')';

        // send and log the message
        $disableNotification = ($request->disableNotification) ?? false;
        TelegramAlert::dispatch($text, $disableNotification, $request->toArray());

        // Create a personal coupon for the partecipant
        $couponValue = $this->generateValue($partecipant, $c);

        $personalCoupon = new Coupon(['value' => $couponValue]);
        $partecipant->personalCoupon()->save($personalCoupon);

        // Associate the coupon with the course
        $c->coupons()->save($personalCoupon);
        
        // Empty the session
        session()->forget('coupon');
        session()->forget('course_id');

        return redirect()->route('partecipant.show', ['slug' => $partecipant->slug])->with(['status' => 'Iscrizione avvenuta con successo!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $partecipant = Partecipant::where('slug', $slug)->first();
        if ($partecipant) {
            $courses = $partecipant->courses;
            return view('partecipants.show')->with(['partecipant' => $partecipant]);
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

    /**
     * Generate a pseudo random value for the coupon
     *
     * @param Partecipant $partecipant
     * @param Course $course
     * @return string
     */
    public function generateValue(Partecipant $partecipant, Course $course)
    {
        do {
            $value = substr($partecipant->surname, 0, 1) . substr($partecipant->name, 0, 3) . random_int(11, 99) . $course->id;
        } while (!Coupon::where('value', $value)->get()->isEmpty());
        return $value;
    }
}
