<?php

declare(strict_types=1);

namespace App\Presentation\Resources;

use App\Application\Features\Posyandus\DTOs\PosyanduDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PosyanduDTO
 */
final class PosyanduResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'district'   => $this->district,
            'location'   => $this->location,
            'lat'        => $this->lat,
            'lng'        => $this->lng,
            'created_at' => $this->createdAt,
        ];
    }
}
