<?php

declare(strict_types=1);

namespace App\Infrastructure\Posyandus\Models;

use App\Infrastructure\Auth\Models\User;
use App\Infrastructure\Children\Models\Child;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Posyandu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'district',
        'location',
        'lat',
        'lng',
    ];

    /**
     * @return HasMany<Child>
     */
    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    /**
     * @return HasMany<User>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
