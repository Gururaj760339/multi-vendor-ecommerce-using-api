<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function cupons(){
        return $this->hasMany(Cupon::class);
    }

    public function vendorEarning(){
        return $this->hasOne(VendorEarning::class);
    }

    public function vendorWithdrawals(){
        return $this->hasMany(VendorWithdrawal::class);
    }
}
