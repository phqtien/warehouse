<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
