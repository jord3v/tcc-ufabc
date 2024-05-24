<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'report_id',
        'invoice',
        'process',
        'reference',	
        'occurrences',
        'price',	
        'due_date',	
        'signature_date'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reference' => 'datetime:Y-m-d',
            'occurrences' => 'array',
            'due_date'  => 'datetime:Y-m-d',
            'signature_date' => 'datetime:Y-m-d'
        ];
    }

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->uuid = str()->uuid();
        });
    }

    /**
     * Format date.
     */
    protected function reference(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => setMonthAndYear($value)
        );
    }

    /**
     * Format date.
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => setPrice($value),
        );
    }

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
