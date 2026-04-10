<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Project;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Project\Commands\AddMember\AddMemberCommand;
use App\Domain\Authorization\Enums\UserRole;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Project\AddMemberRequest;
use App\Presentation\Shared\Traits\ApiResponse;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;

final class AddMemberController extends ApiController
{
    use ApiResponse, HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus,
    ) {}

    public function __invoke(AddMemberRequest $request, string $project_id): JsonResponse
    {
        $this->commandBus->dispatch(new AddMemberCommand(
            projectId: $project_id,
            actorId: $this->getAuthUserId(),
            actorProjectRole: $request->input('_actor_project_role'),
            userId: $request->string('user_id'),
            role: UserRole::from($request->string('role')),
        ));

        return $this->success(message: 'Member added.');
    }
}
