<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeatLayout extends Model
{
    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
