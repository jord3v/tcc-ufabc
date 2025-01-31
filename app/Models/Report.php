<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\{
    Traits\LogsActivity,
    LogOptions
};

class Report extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        
        return LogOptions::defaults()
        ->logFillable()
        ->useLogName('reports')
        ->setDescriptionForEvent(fn(string $eventName) => "<strong>:causer.username</strong> ".events($eventName)." o relatório circunstanciado do fornecedor: <strong>:subject.company.name prestação de serviço em: :subject.location.name - Empenho: :subject.note.number/:subject.note.year</strong>");
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'location_id',
        'note_id',
        'file_id',
        'user_id',
        'manager',
        'department',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function payments()
    {
       return $this->hasMany(Payment::class);
    }
}