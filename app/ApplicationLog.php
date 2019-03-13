<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationLog extends Model
{
    protected $fillable = ['value', 'description', 'status', 'meta'];

    protected $casts = ['value' => 'array'];

    protected $dates = ['created_at'];
}
