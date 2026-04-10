<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Project;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Project\Commands\CreateProject\CreateProjectCommand;
use App\Application\Features\Project\DTOs\CreateProjectDTO;
use App\Application\Features\Project\Queries\ListProjects\ListProjectsQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Project\CreateProjectRequest;
use App\Presentation\Resources\Project\ProjectResource;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ProjectController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->queryBus->dispatch(new ListProjectsQuery(
            actorId: $this->getAuthUserId(),
            filters: $request->only(['status']),
            perPage: (int) $request->input('per_page', 15),
            page: (int) $request->input('page', 1),
        ));

        return $this->paginated(
            resourceClass: ProjectResource::class,
            paginatedResult: $result,
        );
    }

    public function store(CreateProjectRequest $request): JsonResponse
    {
        $project = $this->commandBus->dispatch(new CreateProjectCommand(
            dto: new CreateProjectDTO(
                ownerId: $this->getAuthUserId(),
                name: $request->string('name'),
                slug: $request->string('slug'),
                description: $request->input('description'),
            ),
        ));

        return $this->success(
            data: new ProjectResource($project),
            code: Response::HTTP_CREATED
        );
    }
}
