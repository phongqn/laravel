<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    //
    protected $fillable = ['name', 'link', 'thumb', 'status', 'user_id'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
