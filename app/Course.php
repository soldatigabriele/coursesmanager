<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use softDeletes;

    protected $fillable = ['long_id', 'date', 'limit', 'description'];
    
    public function users()
    {
    	return $this->belongsToMany('App\Partecipant');
    }

    public function manager()
    {
    	return $this->belongsTo('App\User');
    }
}
