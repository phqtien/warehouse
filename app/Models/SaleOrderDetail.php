<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrderDetail extends Model
{
    protected $fillable = [
        'sale_order_id',
        'product_id',
        'quantity',
        'warehouse_id',
        'shelf_id',
        'created_at',
        'updated_at'
    ];
}
