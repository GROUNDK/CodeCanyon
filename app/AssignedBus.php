<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedBus extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(owner::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class);
    }
}
