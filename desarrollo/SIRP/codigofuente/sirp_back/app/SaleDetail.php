<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class SaleDetail extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $connection = 'mysql';
    protected $table = 'sales_detail';
    protected $primaryKey = 'detail_id';


    protected  $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    public function sale()
    {
        return $this->belongsTo('App\Sale', 'sale_id', 'sale_id');
    }
}
