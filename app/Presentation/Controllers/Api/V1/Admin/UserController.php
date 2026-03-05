<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Admin;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Auth\Commands\AssignPosyandu\AssignPosyanduCommand;
use App\Presentation\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class UserController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(Request $request, int $userId): JsonResponse
    {
        $validated = $request->validate([
            'posyandu_id' => ['required', 'integer', 'exists:posyandus,id'],
        ]);

        $this->commandBus->dispatch(new AssignPosyanduCommand(
            userId: $userId,
            posyanduId: (int) $validated['posyandu_id'],
        ));

        return $this->success(null, 'User successfully assigned to Posyandu');
    }
}
