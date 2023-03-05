<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use softDeletes;

    protected $excludedKeys = [];

    protected $fillable = ['long_id', 'date', 'limit', 'description', 'start_date', 'end_date'];
    protected $casts = [
        'start_date'=> 'datetime',
        'end_date'=> 'datetime',
    ];

    // Base table headers: we will add the extra field
    protected $headers = ['nome', 'cognome', 'email', 'telefono', 'regione'];

    public function questions()
    {
        return $this->hasMany('App\Question');
    }

    public function partecipants()
    {
        return $this->belongsToMany('App\Partecipant')->withTimestamps();
    }

    public function subs()
    {
        return $this->partecipants()->count();
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
        $headers = $this->baseHeaders();
        // If the course has partecipants
        if ($this->partecipants()->count()) {
            $this->partecipants()->each(function ($p) use (&$headers){
                $extra = (array) $p->getData();
                $headers = array_merge($headers, array_keys($extra));
            });
        }
        // Delete the duplicated keys
        return collect($headers)->filter(function($key) { return !in_array($key, $this->excludedKeys); })->unique();
    }

    public function extraHeaders()
    {
        $headers = [];
        // If the course has partecipants
        if ($this->partecipants()->count()) {
            $this->partecipants()->each(function ($p) use (&$headers){
                $extra = (array) $p->getData();
                $headers = array_merge($headers, array_keys($extra));
            });
        }
        // Delete the duplicated keys
        return collect($headers)->filter(function($key) { return !in_array($key, $this->excludedKeys); })->unique();
    }

    /**
     * Return the base table headers
     *
     * @return void
     */
    public function baseHeaders()
    {
        return $this->headers;
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
