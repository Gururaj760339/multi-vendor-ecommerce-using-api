<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorEarning extends Model
{
    protected $guarded = [];

    public function vendor(){
        return $this->belongsTo(Vendor::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
