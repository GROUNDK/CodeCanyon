<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $guarded = ['id'];

    public function getUsernameAttribute()
    {
        return $this->name;
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function supportMessage(){
        return $this->hasMany(SupportMessage::class);
    }

}
