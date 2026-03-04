<?php

declare(strict_types=1);

namespace App\Domain\Child\Enums;

enum StuntingStatus: string
{
    case SEVERELY_STUNTED = 'severely_stunted';
    case STUNTED          = 'stunted';
    case NORMAL           = 'normal';
    case TALL             = 'tall';

    public function label(): string
    {
        return match ($this) {
            self::SEVERELY_STUNTED => 'Severely Stunted',
            self::STUNTED          => 'Stunted',
            self::NORMAL           => 'Normal',
            self::TALL             => 'Tall',
        };
    }
}
