<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoldPackage extends Model
{
    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function deposit()
    {
        return $this->hasOne(Deposit::class);
    }
}
