<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Productcategory extends Model
{
    use Notifiable;
    use SoftDeletes;
    protected $connection = 'mysql';
    protected $table = 'products_category';
    protected $primaryKey = 'category_id';


    protected  $hidden = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];
    public function products()
    {
        return $this->belongsTo('App\Products');
    }
}
