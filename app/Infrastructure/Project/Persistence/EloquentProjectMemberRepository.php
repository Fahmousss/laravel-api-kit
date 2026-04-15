<?php

declare(strict_types=1);

namespace App\Infrastructure\Project\Persistence;

use App\Domain\Authorization\Enums\UserRole;
use App\Domain\Project\Entities\ProjectMember as ProjectMemberEntity;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Infrastructure\Project\Models\ProjectMember as ProjectMemberModel;
use App\Infrastructure\Shared\Traits\EntityMapper;
use Illuminate\Support\Facades\Log;

final class EloquentProjectMemberRepository implements ProjectMemberRepositoryInterface
{
    use EntityMapper;

    public function findMember(string $projectId, string $userId): ?ProjectMemberEntity
    {
        $model = ProjectMemberModel::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        return $model ? $this->mapToEntity($model, ProjectMemberEntity::class) : null;
    }

    public function getMemberRole(string $projectId, string $userId): ?UserRole
    {
        $model = ProjectMemberModel::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->first();

        return $model ? $model->role : null;
    }

    public function save(ProjectMemberEntity $member): ProjectMemberEntity
    {
        $model = $member->id
            ? ProjectMemberModel::findOrFail($member->id)
            : new ProjectMemberModel();

        $model->fill([
            'project_id' => $member->projectId,
            'user_id'    => $member->userId,
            'role'       => $member->role->value,
            'joined_at'  => $member->joinedAt ?? now(),
        ])->save();

        return $this->mapToEntity($model->fresh(), ProjectMemberEntity::class);
    }

    public function remove(string $projectId, string $userId): void
    {
        ProjectMemberModel::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->delete();
    }

    public function listMembers(string $projectId): array
    {
        return ProjectMemberModel::where('project_id', $projectId)
            ->with(['user:id,name,email'])
            ->get()
            ->map(function (ProjectMemberModel $m){
                $m->user = $m->user->toArray();
                return $this->mapToEntity($m, ProjectMemberEntity::class);
            })
            ->toArray();
    }

    public function getRolesByUser(string $userId): array
    {
        return ProjectMemberModel::where('user_id', $userId)
            ->pluck('role', 'project_id')
            ->all();
    }
}
