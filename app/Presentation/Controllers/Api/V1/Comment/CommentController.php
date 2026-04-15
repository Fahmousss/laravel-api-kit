<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Comment;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Comment\Commands\CreateComment\CreateCommentCommand;
use App\Application\Features\Comment\Commands\DeleteComment\DeleteCommentCommand;
use App\Application\Features\Comment\Commands\EditComment\EditCommentCommand;
use App\Application\Features\Comment\DTOs\CreateCommentDTO;
use App\Application\Features\Comment\Queries\ListComments\ListCommentsQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Comment\CreateCommentRequest;
use App\Presentation\Requests\Api\V1\Comment\EditCommentRequest;
use App\Presentation\Resources\Comment\CommentResource;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CommentController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
    ) {}

    public function index(Request $request, string $project_id, string $ticket_id): JsonResponse
    {
        $result = $this->queryBus->dispatch(new ListCommentsQuery(
            actor: $this->actor($request),
            ticketId: $ticket_id,
            perPage: (int) $request->input('per_page', 20),
            page: (int) $request->input('page', 1),
        ));

        return $this->paginated(
            data: CommentResource::collection($result->items),
            paginatedResult: $result,
        );
    }

    public function store(CreateCommentRequest $request, string $project_id, string $ticket_id): JsonResponse
    {
        $commentDto = $this->commandBus->dispatch(new CreateCommentCommand(
            actor: $this->actor($request),
            dto: new CreateCommentDTO(
                ticketId: $ticket_id,
                authorId: $this->actor($request)->userId,
                body: $request->body,
                isInternal: $request->boolean('is_internal', false),
            ),
        ));

        return $this->created(data: new CommentResource($commentDto));
    }

    public function update(EditCommentRequest $request, string $project_id, string $ticket_id, string $comment_id): JsonResponse
    {
        $commentDto = $this->commandBus->dispatch(new EditCommentCommand(
            actor: $this->actor($request),
            commentId: $comment_id,
            body: $request->body,
        ));

        return $this->success(data: new CommentResource($commentDto));
    }

    public function destroy(Request $request, string $project_id, string $ticket_id, string $comment_id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteCommentCommand(
            actor: $this->actor($request),
            commentId: $comment_id,
        ));

        return $this->noContent();
    }
}
