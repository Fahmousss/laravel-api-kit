<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Project;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Project\Commands\CreateProject\CreateProjectCommand;
use App\Application\Features\Project\DTOs\CreateProjectDTO;
use App\Application\Features\Project\Queries\GetProject\GetProjectQuery;
use App\Application\Features\Project\Queries\ListProjects\ListProjectsQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Project\CreateProjectRequest;
use App\Presentation\Resources\Project\ProjectResource;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ProjectController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $actor  = $this->actorFromRequest();
        $result = $this->queryBus->dispatch(new ListProjectsQuery(
            actor: $actor,
            filters: $request->only(['status']),
            perPage: (int) $request->input('per_page', 15),
            page: (int) $request->input('page', 1),
        ));

        // Roles are now embedded in ProjectDTO by ListProjectsQueryHandler
        return $this->paginated(
            resourceClass: ProjectResource::class,
            paginatedResult: $result,
        );
    }

    public function show(Request $request, string $project_id): JsonResponse
    {
        $projectDto = $this->queryBus->dispatch(new GetProjectQuery(
            actor:     $this->actor($request),
            projectId: $project_id,
        ));

        return $this->success(data: new ProjectResource($projectDto));
    }

    public function store(CreateProjectRequest $request): JsonResponse
    {
        $actor      = $this->actorFromRequest();
        $projectDto = $this->commandBus->dispatch(new CreateProjectCommand(
            actor: $actor,
            dto: new CreateProjectDTO(
                ownerId: $actor->userId,
                name: $request->string('name'),
                slug: $request->string('slug'),
                description: $request->input('description'),
            ),
        ));

        return $this->created(data: new ProjectResource($projectDto));
    }
}

