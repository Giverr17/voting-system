<?php

namespace App\Enums;

enum Role : string
{
    case USER='user';
    case ADMIN='admin';

    public function label(){
        return match($this){
            self::USER=>'User',
            self::ADMIN=>'Admin',
        };
    }


}
