<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['name', 'surname','slug', 'email', 'region_id'];


    public function region()
    {
        return $this->belongsTo('App\Region');
    }

}
