<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partecipant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'surname', 'slug', 'email', 'phone', 'data', 'region_id'];

    public function courses()
    {
        return $this->belongsToMany('App\Course')->withTimestamps();
    }

    public function getData()
    {
        $data = json_decode($this->data);
        // Unset the coupon, if there
        unset($data->coupon);
        return $data;
    }

    /**
     * The partecipant has a coupon for that course
     *
     * @return void
     */
    public function personalCoupon()
    {
        return $this->hasOne('App\Coupon');
    }

    /**
     * Returns true if the user has used a valid coupon during the subscription
     *
     * @return boolean
     */
    public function hasCoupon()
    {
        return (isset(json_decode($this->data)->coupon)) ? true : false;
    }

    /**
     * Returns true if the user has used a valid coupon during the subscription
     *
     * @return boolean
     */
    public function getCoupon()
    {
        $data = json_decode($this->data, true);
        return (isset($data['coupon'])) ? $data['coupon'] : null;
    }

    public function getCityAttribute()
    {
        return (isset(json_decode($this->data)->city)) ? json_decode($this->data)->city : '';
    }

    public function getSharesAttribute()
    {
        return (isset(json_decode($this->data)->shares)) ? json_decode($this->data)->shares : '1';
    }

    public function getTransportAttribute()
    {
        return (isset(json_decode($this->data)->transport)) ? json_decode($this->data)->transport : '';
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst(strtolower($value));
    }

    public function setSurnameAttribute($value)
    {
        $this->attributes['surname'] = ucfirst(strtolower($value));
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
}
