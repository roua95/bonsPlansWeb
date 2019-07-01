<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //

    protected $fillable = [
        'id', 'url','user_id','plan_id',
    ];

    public function plans()
    {
        return $this->belongsTo(Plan::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
