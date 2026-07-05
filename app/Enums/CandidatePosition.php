<?php

namespace App\Enums;

enum CandidatePosition: string
{
    case AUDITOR = 'auditor';
    case PRO_1 = 'pro_1';
    case PRO_2 = 'pro_2';
    case ASSISTANT_SECRETARY_GENERAL = 'assistant_secretary_general';
    case TECHNICAL_INFORMATION_OFFICER = 'technical_information_officer';
    case MEMBERSHIP_CHAIRPERSON = 'membership_chairperson';
    case ASSISTANT_MEMBERSHIP_CHAIRPERSON_1 = 'assistant_membership_chairperson_1';
    case ASSISTANT_MEMBERSHIP_CHAIRPERSON_2 = 'assistant_membership_chairperson_2';
    case DIRECTOR_OF_MEMBERSHIP_ENGAGEMENT = 'director_of_membership_engagement';
    case PROGRAMS_CHAIRPERSON = 'programs_chairperson';
    case TREASURER = 'treasurer';
    case SECRETARY_GENERAL = 'secretary_general';
    case VICE_PRESIDENT = 'vice_president';
    case PRESIDENT_ELECT = 'president_elect';

    public function label()
    {
        return match ($this) {
            self::AUDITOR => 'Auditor',
            self::PRO_1 => 'PRO 1',
            self::PRO_2 => 'PRO 2',
            self::ASSISTANT_SECRETARY_GENERAL => 'Assistant Secretary General',
            self::TECHNICAL_INFORMATION_OFFICER => 'Technical Information Officer',
            self::MEMBERSHIP_CHAIRPERSON => 'Membership Chairperson',
            self::ASSISTANT_MEMBERSHIP_CHAIRPERSON_1 => 'Assistant Membership Chairperson 1',
            self::ASSISTANT_MEMBERSHIP_CHAIRPERSON_2 => 'Assistant Membership Chairperson 2',
            self::DIRECTOR_OF_MEMBERSHIP_ENGAGEMENT => 'Director of Membership Engagement',
            self::PROGRAMS_CHAIRPERSON => 'Programs Chairperson',
            self::TREASURER => 'Treasurer',
            self::SECRETARY_GENERAL => 'Secretary General',
            self::VICE_PRESIDENT => 'Vice President',
            self::PRESIDENT_ELECT => 'President Elect',
        };
    }

    public static function ordered(): array
    {
        return array_map(
            fn($position) => $position->value,
            self::cases()
        );
    }
}
