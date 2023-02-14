<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCat extends Model
{
    //
    protected $fillable = ['name', 'slug', 'parent_id'];

    public function catPostChild()
    {
        return $this->hasMany(PostCat::class, 'parent_id');
    }

    public function catPostParent()
    {
        return $this->belongsTo(PostCat::class, 'parent_id');
    }
}
