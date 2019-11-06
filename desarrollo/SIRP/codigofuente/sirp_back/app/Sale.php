<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Askedio\SoftCascade\Traits\SoftCascadeTrait;

class Sale extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $connection = 'mysql';
    protected $table = 'sales';
    protected $primaryKey = 'sale_id';
    protected $softCascade = ['detail'];


    public function detail()
    {
        return $this->hasMany('App\SaleDetail', 'sale_id','sale_id' );
    }

    protected  $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];
}
