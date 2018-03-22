<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partecipant extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'surname', 'email', 'phone', 'data'];

    public function courses()
    {

    	return $this->belongsToMany('App\Course');
    }

}
