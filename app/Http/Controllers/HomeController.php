<?php

namespace App\Http\Controllers;

use Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $latest_partecipants = Auth::user()->partecipants()->sortByDesc('created_at')->take(5);

        return view('home')->with(['partecipants' => $latest_partecipants]);
    }
}
