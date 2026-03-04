<?php

declare(strict_types=1);

namespace App\Infrastructure\Child\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Measurement extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'child_id',
        'measured_at',
        'height_cm',
        'weight_kg',
        'stunting_status',
    ];

    protected $casts = [
        'measured_at' => 'date',
        'height_cm'   => 'float',
        'weight_kg'   => 'float',
    ];

    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}
