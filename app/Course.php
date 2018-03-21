<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use softDeletes;

    protected $guarded = ['id'];
    
    public function users()
    {
    	return $this->belongsToMany('App\Users');
    }
}
