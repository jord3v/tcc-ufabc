<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\{
    Traits\LogsActivity,
    LogOptions
};


class Payment extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        
        return LogOptions::defaults()
        ->logFillable()
        ->useLogName('payments')
        ->setDescriptionForEvent(function (string $eventName) {
            $formattedPrice = getPrice($this->price); // Helper para formatar o preço
            $reference = reference($this->reference); // Helper para formatar a referência
            return "<strong>:causer.username</strong> " . events($eventName) . 
                " o pagamento referente ao mês: <strong>$reference com valor de: $formattedPrice do fornecedor: :subject.report.company.name prestação de serviço em: :subject.report.location.name - Empenho: :subject.report.note.number/:subject.report.note.year </strong>";
        });
    }

    public function logDownload(): void
    {
        activity()
            ->performedOn($this)
            ->causedBy(auth()->user())
            ->inLog('reports')
            ->event('download')
            ->withProperties([
                'attributes' => $this->getAttributes(),
                
            ])
            ->log('<strong>'.auth()->user()->username.'</strong> fez o download do relatório circustanciado, referente ao mês: <strong>'.reference($this->reference).' com valor de: '.getPrice($this->price).' do fornecedor: :subject.report.company.name </strong>prestação de serviço em: :subject.report.location.name - Empenho: :subject.report.note.number/:subject.report.note.year');
    }

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
        'status',
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
