<?php

namespace App\Http\Controllers;

use Auth;
use App\Partecipant;
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
        return view('home')->with([
            'partecipants' => Partecipant::orderByDesc('id')->take(15)->get(),
            'errors' => ApplicationLog::orderByDesc('id')->where('status', '0')->take(5)->get(),
            ]);
    }
}
