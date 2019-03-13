<?php

namespace App\Http\Controllers;

use App\ApplicationLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ErrorsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $errors = ApplicationLog::orderByDesc('id')->paginate(20);
        return view('errors.index')->with(['errors' => $errors]);
    }

    /**
     * Display the specified resource.
     *
     * @param  ApplicationLog $log
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationLog $log)
    {
        return view('errors.show')->with(['error' => $log]);
    }
}
