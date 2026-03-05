<?php

namespace App\Infrastructure\Measurements\Models;

use App\Infrastructure\Children\Models\Child;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_id',
        'date',
        'height',
        'weight',
        'lat',
        'lng',
        'z_score',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'height' => 'decimal:2',
            'weight' => 'decimal:2',
            'lat' => 'decimal:8',
            'lng' => 'decimal:8',
            'z_score' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Child, Measurement>
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(Child::class);
    }
}
