<?php

namespace App\Infrastructure\Children\Models;

use App\Infrastructure\Posyandus\Models\Posyandu;
use App\Infrastructure\Measurements\Models\Measurement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Child extends Model
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

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }

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
}
