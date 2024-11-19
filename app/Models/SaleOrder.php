<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    protected $fillable = ['name', 'phone', 'address', 'status'];
}
