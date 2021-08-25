<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $guarded = ['id'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function routes()
    {
        return $this->belongsTo(Route::class);
    }

    public function counterManager()
    {
        return $this->belongsTo(CounterManager::class);
    }

    public function scopeRouteStoppages($query, $array)
    {
        return $query->whereIn('id', $array)
        ->orderByRaw("field(id,".implode(',',$array).")")->get();
    }
}
