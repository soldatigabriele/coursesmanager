<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use softDeletes;

    protected $fillable = ['long_id', 'date', 'limit', 'description'];
    
    public function partecipants()
    {
    	return $this->belongsToMany('App\Partecipant');
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

    public function headers()
    {
        $headers =['nome', 'cognome', 'email', 'telefono'];

        $courseHasPartecipant = $this->partecipants()->first();
        if($courseHasPartecipant){
            $headers = array_merge($headers, array_keys( (array)$this->partecipants()->first()->getData() ));
        }

        return $headers;
    }
}
