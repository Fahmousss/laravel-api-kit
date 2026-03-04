<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Queries\GetAssignedChildren;

use App\Infrastructure\Child\Models\Child;
use Illuminate\Database\Eloquent\Collection;

final class GetAssignedChildrenQueryHandler
{
    /**
     * @return Collection<int, Child>
     */
    public function handle(GetAssignedChildrenQuery $query): Collection
    {
        return Child::with('measurements')
            ->where('posyandu_id', $query->posyanduId)
            ->get();
    }
}
