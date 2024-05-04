<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number', 
        'year', 
        'process', 
        'modality', 
        'modality_process',
        'service',
        'amount', 
        'monthly_payment', 
        'comments', 
        'active',
        'start', 
        'end'
    ];
}
