<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Services;

use App\Application\Features\Auth\Common\Interfaces\SessionServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final readonly class SessionService implements SessionServiceInterface
{
    public function check(): bool
    {
        return Auth::check();
    }

    public function id(): ?int
    {
        $id = Auth::id();

        return $id !== null ? (int) $id : null;
    }

    public function loginById(int $id, bool $remember = false): void
    {
        Auth::loginUsingId($id, $remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function refresh(): void
    {
        Session::invalidate();
        Session::regenerateToken();
    }

    public function regenerate(): void
    {
        Session::regenerate();
    }
}
