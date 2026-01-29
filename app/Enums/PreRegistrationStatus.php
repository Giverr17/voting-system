<?php

namespace App\Enums;

enum PreRegistrationStatus: string
{
    case PENDING =  'pending';
    case REGISTERED =  'registered';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';


    public function label()
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::REGISTERED => 'Registered',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }
    public function color(): string
{
    return match ($this) {
        self::PENDING => 'bg-amber-100 text-amber-800',
        self::REGISTERED => 'bg-blue-500 text-white',
        self::APPROVED => 'bg-green-500 text-white',
        self::REJECTED => 'bg-red-500 text-white',
    };
}
}
