<?php

declare(strict_types=1);

namespace App\Infrastructure\Child\Models;

use App\Infrastructure\Location\Models\Posyandu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Child extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'posyandu_id',
        'name',
        'nik',
        'date_of_birth',
        'gender',
    ];

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }
}
