<?php

declare(strict_types=1);

namespace App\Presentation\Resources\Project;

use App\Domain\Project\Entities\ProjectMember;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProjectMember
 */
final class ProjectMemberResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ProjectMember $member */
        $member = $this->resource;

        return [
            'id'         => $member->id,
            'project_id' => $member->projectId,
            'user_id'    => $member->userId,
            'role'       => $member->role->value,
            'joined_at'  => $member->joinedAt,
            'user'       => $member->user ? [
                'name'  => $member->user['name'],
                'email' => $member->user['email'],
            ] : null,
        ];
    }
}
