<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\{
    Traits\LogsActivity,
    LogOptions
};

class Company extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'commercial_name',
        'cnpj',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        
        return LogOptions::defaults()
        ->logFillable()
        ->useLogName('companies')
        ->setDescriptionForEvent(fn(string $eventName) => "<strong>:causer.username</strong> ".events($eventName)." o prestador de serviço: <strong>:subject.name</strong>");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
       return $this->hasMany(Report::class);
    }
}
