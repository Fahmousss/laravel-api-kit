<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int    $id
 * @property int    $user_id
 * @property string $role
 */
final class UserRole extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'role',
    ];

    /**
     * @return BelongsTo<User, UserRole>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
