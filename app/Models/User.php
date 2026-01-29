<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'pre_registration_id',
        'mat_no',
        'email',
        'password',
        'code',
        'role',
        'department',
        'results_token',
        'level',
        'has_voted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        
        'password',
        'remember_token',
    ];

    public function preRegistration()
    {
        return $this->belongsTo(PreRegistration::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function hasVotedFor($position)
    {
        return $this->votes()->where('position', $position)->exists();
    }

    public function isAdmin(){
        return $this->role === 'admin';
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            
            'ha_voted'=>'boolean',
            'password'=>'hashed',
            'role' => Role::class,
        ];
    }
}
