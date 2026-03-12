<?php

declare(strict_types=1);

namespace App\Infrastructure\Measurements\Models;

use Illuminate\Database\Eloquent\Model;

final class AnthropometryStandard extends Model
{
    protected $fillable = [
        'gender',
        'age_in_months',
        'minus_3_sd',
        'minus_2_sd',
        'minus_1_sd',
        'median',
        'plus_1_sd',
        'plus_2_sd',
        'plus_3_sd',
    ];

    protected function casts(): array
    {
        return [
            'age_in_months' => 'integer',
            'minus_3_sd'    => 'float',
            'minus_2_sd'    => 'float',
            'minus_1_sd'    => 'float',
            'median'        => 'float',
            'plus_1_sd'     => 'float',
            'plus_2_sd'     => 'float',
            'plus_3_sd'     => 'float',
        ];
    }
}
