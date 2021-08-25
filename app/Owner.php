<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Owner extends Authenticatable
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
        'email_verified_at' => 'datetime',
        'address'           => 'object',
        'general_settings'  => 'object',
        'ver_code_send_at'  => 'datetime'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function login_logs()
    {
        return $this->hasMany(OwnerLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',0);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function soldPackages()
    {
        return $this->hasMany(SoldPackage::class);
    }

    public function seatLayouts()
    {
        return $this->hasMany(SeatLayout::class);
    }

    public function fleetTypes()
    {
        return $this->hasMany(FleetType::class);
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
    public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function assignedBuses()
    {
        return $this->hasMany(AssignedBus::class);
    }

    public function coAdmins()
    {
        return $this->hasMany(CoOwner::class);
    }

    public function counters()
    {
        return $this->hasMany(Counter::class);
    }

    public function counterManagers()
    {
        return $this->hasMany(CounterManager::class);
    }

    public function ticketPrices()
    {
        return $this->hasMany(TicketPrice::class);
    }

    public function bookedTickets()
    {
        return $this->hasMany(BookedTicket::class)->where('booked_tickets.status', '1');
    }

    public function canceledTickets()
    {
        return $this->hasMany(BookedTicket::class)->where('booked_tickets.status', '0');
    }

    public function activePackages()
    {
        return $this->hasMany(SoldPackage::class)->whereStatus('1')->where('ends_at', '>', Carbon::now())->orderByDesc('ends_at')->get();
    }


    public function boughtPackages()
    {
        return $this->hasMany(SoldPackage::class)->where('status', '!=' ,'0')->get();
    }

    public function scopeActive()
    {
        return $this->where('status', 1)->where('ev', 1)->where('sv', 1);
    }

    public function scopeBanned()
    {
        return $this->where('status', 0);
    }

    public function scopeEmailUnverified()
    {
        return $this->where('ev', 0);
    }

    public function scopeSmsUnverified()
    {
        return $this->where('sv', 0);
    }
    public function scopeEmailVerified()
    {
        return $this->where('ev', 1);
    }

    public function scopeSmsVerified()
    {
        return $this->where('sv', 1);
    }

}
