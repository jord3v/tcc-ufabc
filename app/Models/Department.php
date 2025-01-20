<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function sectors()
    {
       return $this->hasMany(Sector::class);
    }
}
