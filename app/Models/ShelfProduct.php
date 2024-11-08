<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShelfProduct extends Model
{
    protected $fillable = ['shelf_id', 'product_id', 'quantity'];

}
