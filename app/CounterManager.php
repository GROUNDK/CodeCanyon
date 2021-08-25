<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CounterManager extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'address'           => 'object',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function counter()
    {
        return $this->hasOne(Counter::class);
    }

    public function tickets()
    {
        return $this->hasMany(BookedTicket::class);
    }

    public function bookedTickets()
    {
        return $this->hasMany(BookedTicket::class)->whereStatus('1');
    }

    public function canceledTickets()
    {
        return $this->hasMany(BookedTicket::class)->whereStatus('0');
    }
}
