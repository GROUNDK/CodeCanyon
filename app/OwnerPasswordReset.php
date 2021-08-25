<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OwnerPasswordReset extends Model
{
    protected $table = "owner_password_resets";
    protected $guarded = ['id'];
    public $timestamps = false;
}
