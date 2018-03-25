<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{



    public function partecipants()
    {
        return $this->hasMany('App\Partecipant');
    }

    public function newsletters()
    {
        return $this->hasMany('App\Newsletter');
    }

}
