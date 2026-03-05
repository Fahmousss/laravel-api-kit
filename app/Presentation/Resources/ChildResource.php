<?php

declare(strict_types=1);

namespace App\Presentation\Resources;

use App\Application\Features\Children\DTOs\ChildDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ChildDTO
 */
final class ChildResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'posyandu_id' => $this->posyanduId,
            'nik'         => $this->nik,
            'name'        => $this->name,
            'dob'         => $this->dob,
            'gender'      => $this->gender,
            'parent_name' => $this->parentName,
            'created_at'  => $this->createdAt,
        ];
    }
}
