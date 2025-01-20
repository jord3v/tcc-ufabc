<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function department()
    {
        // não testado
        return $this->belongsTo(Department::class);
    }

    public function notes()
    {
        // não testado
        return $this->hasMany(Note::class);
    }
}
