<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCat extends Model
{
    //
    protected $fillable = ['name', 'slug', 'parent_id'];

    public function catProductChild()
    {
        return $this->hasMany(ProductCat::class, 'parent_id');
    }

    public function catProductParent()
    {
        return $this->belongsTo(ProductCat::class, 'parent_id');
    }
}
