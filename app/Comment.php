<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $fillable = [
        'id','text','user_id','plan_id',
    ];
    public function plans()
    {
        return $this->belongsTo(Plan::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->belongsTo(Plan::class);
    }
}
