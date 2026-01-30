<?php

namespace App\Enums;

use function PHPSTORM_META\map;

enum CandidatePosition: string
{
    case PRESIDENT =  'president';
    case VICE_PRESIDENT ='vice_president';
    case SECRETARY_GENERAL = 'secretary_general';
    case FINANCIAL_SECRETARY = 'finicial_secretary';
    case ASSISTANT_SECRETARY_GENERAL = 'assistant_secretary_general';
    case DIRECTOR_OF_SOCIALS = 'director_of_socials';
    case DIRECTOR_OF_SPORTS = 'director_of_sports';
    case DIRECTOR_OF_WELFARE = 'director_of_welfare';
    case PUBLIC_RELATION_OFFICER = 'public_relation_officer';


    public function label()
    {
        return match ($this) {
            self::PRESIDENT => 'President',
            self::VICE_PRESIDENT => 'Vice president',
            self::SECRETARY_GENERAL => 'Secretary General ',
            self::FINANCIAL_SECRETARY => 'Financial Secretary ',
            self::ASSISTANT_SECRETARY_GENERAL => 'Assistant Secretary General',
            self::DIRECTOR_OF_SOCIALS => 'Director Of Socials',
            self::DIRECTOR_OF_SPORTS => 'Director Of Sports',
            self::DIRECTOR_OF_WELFARE => 'Director Of Welfare',
            self::PUBLIC_RELATION_OFFICER => 'Public Relation Officer',
        };
    }

    public static function ordered(): array
    {
        return array_map(
            fn($position)=>$position->value,
            self::cases()
        );
        
    }
  
}
