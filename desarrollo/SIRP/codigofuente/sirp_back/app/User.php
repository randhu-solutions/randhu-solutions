<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    protected $connection = 'mysql';
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected  $hidden = [
        'password',
        'updated_at',
        'created_at',
        'deleted_at'
    ];
    public function session()
    {
        return $this->hasOne('App\Usersession');
    }

    public function profile()
    {
        return $this->hasOne('App\Userprofile','user_id','user_id');
    }
}
