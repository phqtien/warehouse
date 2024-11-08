<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = ['order_date', 'status'];

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }
}

