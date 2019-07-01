<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category  extends Model
{
    //
    protected $fillable = [
        'id','category_name',
    ];
    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }
}