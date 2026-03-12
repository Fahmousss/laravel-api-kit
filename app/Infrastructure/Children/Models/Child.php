<?php

declare(strict_types=1);

namespace App\Infrastructure\Children\Models;

use App\Infrastructure\Measurements\Models\Measurement;
use App\Infrastructure\Posyandus\Models\Posyandu;
use Database\Factories\ChildFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'posyandu_id',
        'nik',
        'name',
        'dob',
        'gender',
        'parent_name',
    ];

    /**
     * @return BelongsTo<Posyandu, Child>
     */
    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    /**
     * @return HasMany<Measurement>
     */
    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    protected static function newFactory()
    {
        return ChildFactory::new();
    }

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }
}
