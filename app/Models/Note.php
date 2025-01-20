<?php

namespace App\Models;

use App\Scopes\SectorScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Note extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new SectorScope);
    }

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
        'end',
        'sector_id'
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['sector_name'];

     /**
     * Format date.
     */
    protected function amount(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => setPrice($value),
        );
    }

    protected function sectorName(): Attribute
    {
        return Attribute::make(
            get: fn () => DB::table('sectors')
                ->where('id', $this->sector_id)
                ->value('name'), // Busca apenas o nome do setor com base no sector_id
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

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}
