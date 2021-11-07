<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Course;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Check if a given coupon is valid
     *
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        // TODO Set some anti-bruteforce system here

        // Get the values of the active coupons related to the course
        $validCoupons = Course::findOrFail($request->course_id)
            ->coupons
            ->where('active', true)
            ->pluck('value')->toArray();

        // If the coupon is between those coupons, return "ok" and save the token in session
        $result['status'] = 'ko';
        if(in_array($request->coupon, $validCoupons)){
            $result['status'] = 'ok';
            session()->put(['coupon' => $request->coupon]);
            session()->put(['course_id' => $request->course_id]);
        } 
        return $result;
    }

    /**
     * Unset the coupon and the selected course from session
     *
     * @return string JSON
     */
    public function unset()
    {
        session()->forget('coupon');
        session()->forget('course_id');
        return ['status' => 'ok'];
    }
}
