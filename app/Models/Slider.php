<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slider extends Model
{
    //
    use SoftDeletes;
    protected  $fillable  = ['name', 'status', 'number_order', 'slider_link','slug','user_id'];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
