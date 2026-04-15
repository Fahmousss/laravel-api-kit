<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1\ActivityLog;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\ActivityLog\Queries\ListActivityLogs\ListActivityLogsQuery;
use App\Presentation\Controllers\Api\ApiController;
use App\Presentation\Resources\ActivityLog\ActivityLogResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ActivityLogController extends ApiController
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function __invoke(Request $request, string $project_id, string $ticket_id): JsonResponse
    {
        $result = $this->queryBus->dispatch(new ListActivityLogsQuery(
            ticketId: $ticket_id,
            perPage: (int) $request->input('per_page', 20),
            page: (int) $request->input('page', 1),
        ));

        return $this->paginated(
            data: ActivityLogResource::collection($result->items),
            paginatedResult: $result,
        );
    }
}
