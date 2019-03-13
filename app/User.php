<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token', 'active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function courses()
    {
        return $this->hasMany('App\Course');
    }

    /* Finds all the partecipants to a user courses
     *  if no user_id is provided, the auth id is retrieved
     *
     */
    public static function partecipants($user_id = null)
    {
        $user = ($user_id) ? User::find($user_id) : \Auth::user();
        $all = new Collection;
        foreach ($user->courses as $course) {
            $all = $all->merge($course->partecipants);
        }
        return $all;
    }
}
