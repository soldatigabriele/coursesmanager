<?php

namespace App\Http\Controllers;

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
        $parts = Partecipant::all();
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
     * @return \Illuminate\Http\Response
     */
    public function scheda1()
    {
        // return the course creation form
        return view('forms.scheda1')->with(['regions' => Region::all(), 'courses' => Course::whereNotIn('id', [84,85])->where('end_date', '>', Carbon::today())->get()]);
    }

    /**
     * Show the scheda 2 form.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheda2()
    {
        // return the course creation form
        return view('forms.scheda2')->with(['regions' => Region::all(), 'courses' => Course::whereNotIn('id', [84,85])->where('end_date', '>', Carbon::today())->get()]);
    }

    /**
     * Show the scheda 3 form.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheda3()
    {
        // return the course creation form
        return view('forms.scheda3')->with(['regions' => Region::all(), 'courses' => Course::whereNotIn('id', [84,85])->where('end_date', '>', Carbon::today())->get()]);
    }

    /**
     * Show the scheda 4 form.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheda4(Request $request)
    {
        // return the course creation form
        return view('forms.scheda4')
            ->with([
                'regions' => Region::all(),
                'courses' => Course::whereNotIn('id', [84,85])->where('end_date', '>', Carbon::today())->get(),
                'mele' => $request->m, // If m is set, show the "mele" field
            ]);
    }

    /**
     * Show the scheda 4 form.
     *
     * @return \Illuminate\Http\Response
     */
    public function scheda5()
    {
        // return the course creation form
        return view('forms.scheda5')
            ->with([
                'regions' => Region::all(),
                'courses' => Course::whereNotIn('id', [84,85])->where('end_date', '>', Carbon::today())->get(),
            ]);
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
            // 'city' => 'required',
            'region_id' => new RegionRule,
            'course_id' => [new CourseRule, 'required'],
        ];

        if (env('REQUIRE_CAPTCHA') === 'yes') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            $data = ((array_merge($validation->getData(), ['errors' => $validation->errors()->getMessages()])));
            (new Logger)->log('0', 'Partecipant Subscription Error', $data, $request);
            return redirect()->back()
                ->withErrors($validation)
                ->withInput();
        }

        (new Logger)->log('1', 'Partecipant Subscription Success', $request->all(), $request);

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
        array_forget($data, 'create_coupon');

        // format the data
        $tempData = array_map('ucfirst', (array_map('strtolower', $data)));

        // Check if the user has a valid coupon
        if ($coupon = session()->get('coupon')) {
            // Double check coupon's validity and increase the counter
            if ($couponModel = Coupon::where('value', $coupon)->first()) {
                $couponModel->increment('usages');
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
        $course = Course::find($course_id);
        $url = url(route('courses.index') . '?course_id=' . $course->id . '&partecipant_id=' . $partecipant->fresh()->id);
        $text = '*' . $partecipant->name . ' ' . $partecipant->surname . '* - *' . $partecipant->email . '* *' . $partecipant->phone . '* si è iscritto al corso *' . $course->long_id . '* del ' . $course->date . ' [Vai alla scheda](' . $url . ')';

        // send and log the message
        $disableNotification = ($request->disableNotification) ?? false;
        TelegramAlert::dispatch($text, $disableNotification, $request->toArray());

        if (isset($request->create_coupon)) {
            // Create a personal coupon for the partecipant
            $couponValue = $this->generateValue($partecipant, $course);

            $personalCoupon = new Coupon(['value' => $couponValue]);
            $partecipant->personalCoupon()->save($personalCoupon);

            // Associate the coupon with the course
            $course->coupons()->save($personalCoupon);
        }

        // Empty the session and show the confirmation alert
        session()->forget('coupon');
        session()->forget('course_id');
        session()->flash('status', 'Iscrizione al corso avvenuta con successo!');

        return redirect()->route('partecipant.show', ['slug' => $partecipant->slug]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $partecipant = Partecipant::withTrashed()->where('slug', $slug)->first();
        if ($partecipant) {
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
        return redirect()->route('partecipant.show', $partecipant->slug);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($partecipantId)
    {
        $partecipant = Partecipant::withTrashed()->find($partecipantId);
        $partecipant->restore();
        return redirect()->route('partecipant.show', $partecipant->slug);
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

    /**
     * Return the list of deleted partecipants
     *
     * @return \Illuminate\Http\Response
     */
    public function deleted()
    {
        $partecipants = Partecipant::withTrashed()->whereNotNull('deleted_at')->get();
        return view('partecipants.deleted')->with(['partecipants' => $partecipants]);
    }
}
