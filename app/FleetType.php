<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FleetType extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    protected $casts = [
        'seats' => 'object'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

}
