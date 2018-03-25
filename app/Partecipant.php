<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partecipant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'surname', 'slug', 'email', 'phone', 'data'];

    public function courses()
    {
    	return $this->belongsToMany('App\Course');
    }

    public function getData()
    {
    	return json_decode($this->data);
    }

}
