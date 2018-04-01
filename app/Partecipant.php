<?php

namespace App;

use Auth;
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
    	return json_decode($this->data);
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
