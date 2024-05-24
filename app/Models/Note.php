<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start' => 'datetime:Y-m-d',
        'end'  => 'datetime:Y-m-d',
    ];

     /**
     * Format date.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => setPrice($value),
        );
    }

    protected function monthlyPayment(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => setPrice($value),
        );
    }

    public function reports()
    {
       return $this->hasMany(Report::class);
    }
}
