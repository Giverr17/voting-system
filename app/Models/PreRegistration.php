<?php

namespace App\Models;

use App\Enums\PreRegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class PreRegistration extends Model
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'mat_no',
        'full_name',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
    protected function casts(): array
    {
        return [
            'status' => PreRegistrationStatus::class,
        ];
    }
}
