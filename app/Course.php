<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use softDeletes;

    protected $fillable = ['long_id', 'date', 'limit', 'description', 'start_date', 'end_date'];

    // protected $casts = [
    //     'start_date' => 'date',
    //     'end_date' => 'date',
    // ];
    public function partecipants()
    {
        return $this->belongsToMany('App\Partecipant')->withTimestamps();
    }

    public function subs()
    {
        return $this->partecipants()->count();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getVegetarians()
    {
        // $this->partecipants()->where('')->groupBy()->get();
    }

    public function getDistinctEmails($value)
    {
        return ($this->partecipants->unique($value)->values()->all());
    }

    public function headers()
    {
        $headers = ['nome', 'cognome', 'email', 'telefono', 'regione'];

        $courseHasPartecipant = $this->partecipants()->first();
        if ($courseHasPartecipant) {
            $headers = array_merge($headers, array_keys((array) $this->partecipants()->first()->getData()));
        }

        return $headers;
    }

    /**
     * A course has many coupons
     *
     * @return void
     */
    public function coupons()
    {
        return $this->hasMany('App\Coupon');
    }
}
