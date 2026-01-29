<?php

namespace App\Models;

use App\Enums\CandidatePosition;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'full_name',
        'position_applied',
        'mat_no',
        'department',
        'level',
        'slogan',
        'image'
    ];

    public function votes(){
        return $this->hasMany(Vote::class,'candidate_id');
    }

    public function getVoteCountAttribute()
    {
        return $this->votes()->count();
    }
    protected function casts(): array
    {
        return [
            'position_applied' => CandidatePosition::class,
        ];
    }
}
