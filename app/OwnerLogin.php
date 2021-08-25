<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OwnerLogin extends Model
{
    protected $guarded = ['id'];


    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
