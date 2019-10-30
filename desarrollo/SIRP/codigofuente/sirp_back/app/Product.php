<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model
{
    use SoftDeletes;
    use Notifiable;
    protected $connection = 'mysql';
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected  $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'brand_id',
        'category_id'
    ];
    public function category()
    {
        return $this->hasOne('App\Productcategory','category_id','category_id');
    }

    public function brand()
    {
        return $this->hasOne('App\Productbrand','brand_id','brand_id');
    }
}
