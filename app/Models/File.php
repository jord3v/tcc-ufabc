<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\{
    Traits\LogsActivity,
    LogOptions
};

class File extends Model
{
    use HasFactory, LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'path',
        'active',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        
        return LogOptions::defaults()
        ->logFillable()
        ->useLogName('files')
        ->setDescriptionForEvent(fn(string $eventName) => "<strong>:causer.username</strong> ".events($eventName)." o arquivo: <strong>:subject.filename</strong>");
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
