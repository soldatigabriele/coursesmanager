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
}
