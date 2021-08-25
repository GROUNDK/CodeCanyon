<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketPrice extends Model
{

    protected $guarded = ['id'];


    protected $table = "ticket_prices";


    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function fleetType()
    {
        return $this->belongsTo(FleetType::class);
    }

    public function prices()
    {
        return $this->hasMany(TicketPriceByStoppage::class);
    }
}
