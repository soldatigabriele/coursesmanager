<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use softDeletes;

    protected $fillable = ['long_id', 'date', 'limit', 'description', 'start_date', 'end_date'];

    // Base table headers: we will add the extra field
    protected $headers = ['nome', 'cognome', 'email', 'telefono', 'regione'];

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
        $headers = $this->baseHeaders();
        // If the course has partecipants
        if ($this->partecipants()->count()) {
            $this->partecipants()->each(function ($p) use (&$headers){
                $extra = (array) $p->getData();
                $headers = array_merge($headers, array_keys($extra));
            });
        }
        // Delete the duplicated keys
        return collect($headers)->unique();
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
        return collect($headers)->unique();
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
