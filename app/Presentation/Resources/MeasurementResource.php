<?php

declare(strict_types=1);

namespace App\Presentation\Resources;

use App\Application\Features\Measurements\DTOs\MeasurementDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MeasurementDTO
 */
final class MeasurementResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'child_id'   => $this->childId,
            'date'       => $this->date,
            'height'     => $this->height,
            'weight'     => $this->weight,
            'lat'        => $this->lat,
            'lng'        => $this->lng,
            'z_score'    => $this->zScore,
            'status'     => $this->status,
            'created_at' => $this->createdAt,
        ];
    }
}
