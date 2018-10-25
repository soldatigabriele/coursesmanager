<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use SoftDeletes;
    protected $fillable = ['value', 'active'];

    public function hashed()
    {
        return (hash('sha1', $this->value));
    }
    
    /**
     * A coupon is bound to a partecipant
     *
     * @return void
     */
    public function partecipant()
    {
        return $this->belongsTo('App\Partecipant');
    }

    /**
     * A coupon is valid only for a specific course
     *
     * @return void
     */
    public function course()
    {
        return $this->belongsTo('App\Course');
    }

    /**
     * Coupon value is capitalised on save
     *
     * @param string $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = strtoupper($value);
    }
}
