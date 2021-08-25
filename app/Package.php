<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = ['id'];

    public function soldPackages()
    {
        return $this->hasMany(SoldPackage::class);
    }


}
