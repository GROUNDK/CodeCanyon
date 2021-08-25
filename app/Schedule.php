<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
