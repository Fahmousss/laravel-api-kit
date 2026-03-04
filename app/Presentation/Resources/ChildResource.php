<?php

declare(strict_types=1);

namespace App\Presentation\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ChildResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'posyandu_id'   => $this->posyanduId ?? $this->posyandu_id,
            'name'          => $this->name,
            'nik'           => $this->nik,
            'date_of_birth' => $this->dateOfBirth ?? $this->date_of_birth,
            'gender'        => $this->gender,
            'posyandu'      => new PosyanduResource($this->whenLoaded('posyandu')),
            'measurements'  => MeasurementResource::collection($this->whenLoaded('measurements')),
        ];
    }
}
