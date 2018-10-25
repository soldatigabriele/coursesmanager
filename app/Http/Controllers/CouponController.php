<?php

namespace App\Http\Controllers;

use App\Coupon;
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

        // If the coupon is valid, return ok and a token to validate the registration
        $validCoupons = Coupon::whereActive(true)->pluck('value')->toArray();
        $result['status'] = 'ko';
        if(in_array($request->coupon, $validCoupons)){
            $result['status'] = 'ok';
            session()->put(['coupon' => $request->coupon]);
        } 
        return $result;
    }
}
