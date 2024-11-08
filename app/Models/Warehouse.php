<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['address', 'name'];

    public function shelves()
    {
        return $this->hasMany(Shelf::class);
    }
}

