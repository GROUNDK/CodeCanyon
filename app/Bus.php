<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{

    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function fleetType()
    {
        return $this->belongsTo(FleetType::class);
    }

    public function assignedBuses()
    {
        return $this->hasMany(AssignedBus::class);
    }

    public function trip()
    {
        return $this->hasOne(Trip::class);
    }
}
