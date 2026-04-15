<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\Notification;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Notification\Queries\ListNotifications\ListNotificationsQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\Notification\NotificationResource;
use App\Presentation\Shared\Traits\HasAuthenticatedUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GetNotificationController extends ApiController
{
    use HasAuthenticatedUser;

    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->queryBus->dispatch(new ListNotificationsQuery(
            userId: $this->getAuthUserId(),
            unreadOnly: $request->boolean('unread_only'),
            perPage: (int) $request->input('per_page', 20),
            page: (int) $request->input('page', 1),
        ));

        return $this->paginated(
            data: NotificationResource::collection($result->items),
            paginatedResult: $result,
        );
    }
}
