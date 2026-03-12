<?php

declare(strict_types=1);

namespace App\Application\Features\Posyandus\Queries\GetPosyandusByUserId;

use App\Application\Features\Posyandus\DTOs\PosyanduDTO;
use App\Domain\Shared\Pagination\PaginatedResult;
use Illuminate\Support\Facades\DB;

final class GetPosyandusByUserIdQueryHandler
{
    public function handle(GetPosyandusByUserIdQuery $query): PaginatedResult
    {
        $paginator = DB::table('posyandus')
            ->join('users', 'posyandus.id', '=', 'users.posyandu_id')
            ->where('users.id', $query->userId)
            ->select('posyandus.*')
            ->paginate($query->perPage, ['*'], 'page', $query->page);

        $dtos = array_map(static fn ($record): PosyanduDTO => new PosyanduDTO(
            id: $record->id,
            name: $record->name,
            district: $record->district,
            location: $record->location,
            lat: $record->lat ? (float) $record->lat : null,
            lng: $record->lng ? (float) $record->lng : null,
            createdAt: $record->created_at,
            updatedAt: $record->updated_at,
        ), $paginator->items());

        return new PaginatedResult(
            items: $dtos,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage: $paginator->lastPage(),
        );
    }
}
