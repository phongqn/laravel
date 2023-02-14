<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ['name', 'slug', 'product_code', 'user_id', 'product_thumb', 'status', 'cat_id', 'desc', 'detail', 'qty', 'price_sale', 'original_price', 'product_selling', 'outstanding_product'];
    public function thumb()
    {
        return $this->hasMany(ProductThumb::class, 'product_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function category()
    {
        return $this->belongsTo(ProductCat::class, 'cat_id');
    }
}
