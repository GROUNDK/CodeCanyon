<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GatewayCurrency extends Model
{
    protected $casts = ['status' => 'boolean'];
    protected $guarded = ['id'];

    // Relation
    public function method()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    public function currencyIdentifier()
    {
        return $this->name ?? $this->method->name . ' ' . $this->currency;
    }

    public function scopeBaseCurrency()
    {
        return $this->method->crypto == 1 ? 'USD' : $this->currency;
    }

    public function scopeBaseSymbol()
    {
        return $this->method->crypto == 1 ? '$' : $this->symbol;
    }

    public function scopeMethodImage()
    {
        return ($this->image) ? asset(imagePath()['gateway']['path'] .'/' . $this->image) : (($this->method->image) ? asset(imagePath()['gateway']['path'] . '/' . $this->method->image) :  asset(imagePath()['image']['default']));
    }
}
