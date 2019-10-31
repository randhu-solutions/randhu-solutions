<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Usersession extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $connection = 'mysql';
    protected $table = 'users_session';
    protected $primaryKey = 'session_id';

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
}
