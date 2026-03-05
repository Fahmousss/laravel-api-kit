<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Models;

use App\Infrastructure\Posyandus\Models\Posyandu;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property null|Carbon $email_verified_at
 * @property string      $password
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 */
#[UseFactory(UserFactory::class)]
final class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'posyandu_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The roles assigned to the user.
     * We use a pivot table, but we cast the `role` column to the Domain Enum.
     *
     * @return BelongsToMany<User>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            related: self::class, // Dummy related model because there is no 'Role' model
            table: 'role_user',
            foreignPivotKey: 'user_id',
            // We only need the string value from the pivot table
        )->withPivot('role');
    }

    /**
     * @return BelongsTo<Posyandu, User>
     */
    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }
}
