<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'day_off' => 'array'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function fleetType()
    {
        return $this->belongsTo(FleetType::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function bookedTickets()
    {
        return $this->hasMany(BookedTicket::class)->whereStatus('1');
    }

    public function canceledTickets()
    {
        return $this->hasMany(BookedTicket::class)->whereStatus('0');
    }

    public function assignedBuses()
    {
        return $this->hasMany(AssignedBus::class);
    }

    public function startingPoint()
    {
        return $this->belongsTo(Counter::class, 'starting_point');
    }

    public function destinationPoint()
    {
        return $this->belongsTo(Counter::class, 'destination_point');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

}
