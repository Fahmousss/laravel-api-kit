<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Auth;

use App\Infrastructure\Auth\Models\User;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\ResendVerificationRequest;
use App\Presentation\Requests\Api\V1\VerifyEmailRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

final class EmailVerificationController extends ApiController
{
    public function verify(VerifyEmailRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return $this->success(message: 'Email already verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return $this->success(message: 'Email verified successfully');
    }

    public function resend(ResendVerificationRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();

        if (! $user) {
            return $this->notFound('User not found');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->error('Email already verified', 400);
        }

        $user->sendEmailVerificationNotification();

        return $this->success(message: 'Verification email sent successfully');
    }
}
