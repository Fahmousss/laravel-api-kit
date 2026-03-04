<?php

declare(strict_types=1);

namespace App\Infrastructure\Location\Persistence;

use App\Domain\Location\Entities\PosyanduEntity;
use App\Domain\Location\Repositories\PosyanduRepositoryInterface;
use App\Domain\Location\ValueObjects\GeoCoordinate;
use App\Infrastructure\Location\Models\Posyandu;
use App\Infrastructure\Shared\Traits\EntityMapper;

final class EloquentPosyanduRepository implements PosyanduRepositoryInterface
{
    use EntityMapper;

    public function findById(string $id): ?PosyanduEntity
    {
        $model = Posyandu::find($id);

        if (! $model) {
            return null;
        }

        return new PosyanduEntity(
            id: $model->id,
            name: $model->name,
            coordinates: $model->coordinates ? new GeoCoordinate(
                latitude: $model->coordinates->getLat(),
                longitude: $model->coordinates->getLng()
            ) : null,
        );
    }

    public function save(PosyanduEntity $posyandu): PosyanduEntity
    {
        $model = Posyandu::updateOrCreate(
            ['id' => $posyandu->id],
            [
                'name' => $posyandu->name,
                // Assuming standard spatial casting is handled by DB facade or model casts
                'coordinates' => $posyandu->coordinates
                    ? \Illuminate\Support\Facades\DB::raw(sprintf("ST_GeomFromText('POINT(%f %f)')", $posyandu->coordinates->longitude, $posyandu->coordinates->latitude))
                    : null,
            ]
        );

        return $this->findById($model->id);
    }
}
