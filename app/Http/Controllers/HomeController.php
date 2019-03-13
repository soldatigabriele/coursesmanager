<?php

namespace App\Http\Controllers;

use Auth;
use App\ApplicationLog;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $latest_partecipants = Auth::user()->partecipants()->sortByDesc('created_at')->take(15);

        return view('home')->with([
            'partecipants' => $latest_partecipants,
            'errors' => ApplicationLog::orderByDesc('id')->where('status', '0')->take(5)->get(),
            ]);
    }
}
