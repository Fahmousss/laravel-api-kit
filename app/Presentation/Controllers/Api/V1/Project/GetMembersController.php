<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Project;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Project\Queries\ListProjectMembers\ListProjectMembersQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\Project\ProjectMemberResource;
use App\Presentation\Shared\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

final class GetMembersController extends ApiController
{
    use ApiResponse;

    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(string $project_id): JsonResponse
    {
        $members = $this->queryBus->dispatch(new ListProjectMembersQuery($project_id));

        return $this->success(ProjectMemberResource::collection($members));
    }
}
