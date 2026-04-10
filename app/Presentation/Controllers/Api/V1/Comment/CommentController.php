<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Comment;

use App\Application\Contracts\CommandBusInterface;
use App\Application\Features\Comment\Commands\CreateComment\CreateCommentCommand;
use App\Application\Features\Comment\DTOs\CreateCommentDTO;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Requests\Api\V1\Comment\CreateCommentRequest;
use App\Presentation\Resources\Comment\CommentResource;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CommentController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private CommandBusInterface $commandBus,
    ) {}

    public function __invoke(CreateCommentRequest $request, string $project_id, string $ticket_id): JsonResponse
    {
        $comment = $this->commandBus->dispatch(new CreateCommentCommand(
            dto: new CreateCommentDTO(
                ticketId: $ticket_id,
                authorId: $this->getAuthUserId(),
                body: $request->string('body'),
                isInternal: $request->boolean('is_internal', false),
            ),
        ));

        return $this->success(
            data: new CommentResource($comment),
            code: Response::HTTP_CREATED
        );
    }
}
