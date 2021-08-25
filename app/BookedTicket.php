<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookedTicket extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'seats'             => 'array',
        'passenger_details' => 'array',
        'source_destination'=> 'array'
    ];

    public function counterManager()
    {
        return $this->belongsTo(CounterManager::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

}
