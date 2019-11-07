<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Userprofile extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $connection = 'mysql';
    protected $table = 'users_profile';
    protected $primaryKey = 'profile_id';


    protected  $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
