<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Admin;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Project\Commands\DeleteProject\DeleteProjectCommand;
use App\Application\Features\Project\Commands\UpdateProject\UpdateProjectCommand;
use App\Application\Features\Project\Queries\GetProject\GetProjectQuery;
use App\Application\Features\Project\Queries\ListProjects\ListProjectsQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Admin\UpdateProjectRequest;
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

        return $this->paginated(
            paginatedResult: $result,
            resourceClass: ProjectResource::class,
        );
    }

    public function show(Request $request, string $project_id): JsonResponse
    {
        $dto = $this->queryBus->dispatch(new GetProjectQuery(
            actor: $this->actor($request),
            projectId: $project_id,
        ));

        return $this->success(data: new ProjectResource($dto));
    }

    public function update(UpdateProjectRequest $request, string $project_id): JsonResponse
    {
        $dto = $this->commandBus->dispatch(new UpdateProjectCommand(
            actor: $this->actorFromRequest(),
            id: $project_id,
            name: $request->has('name') ? (string) $request->input('name') : null,
            description: $request->has('description') ? (string) $request->input('description') : null,
            status: $request->has('status') ? (string) $request->input('status') : null,
        ));

        return $this->success(data: new ProjectResource($dto));
    }

    public function destroy(string $project_id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteProjectCommand(
            actor: $this->actorFromRequest(),
            id: $project_id,
        ));

        return $this->noContent();
    }
}
