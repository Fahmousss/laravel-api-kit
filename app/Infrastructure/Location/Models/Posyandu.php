<?php

declare(strict_types=1);

namespace App\Infrastructure\Location\Models;

use App\Infrastructure\Child\Models\Child;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Posyandu extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'coordinates',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }
}
