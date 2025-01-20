<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public static function avatar()
    {
        return 'https://picsum.photos/300/300';
    }

    // No modelo User
    public function getSectorsGroupedByDepartment()
    {
        return $this->sectors()
            ->with('department')
            ->get()
            ->groupBy(fn($sector) => $sector->department->name);
    }


    public function files()
    {
       return $this->hasMany(File::class);
    }

    public function locations()
    {
       return $this->hasMany(Location::class);
    }

    public function companies()
    {
       return $this->hasMany(Company::class);
    }

    public function reports()
    {
       return $this->hasMany(Report::class);
    }

    public function sectors()
    {
        return $this->belongsToMany(Sector::class);
    }
}
