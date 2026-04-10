<?php

namespace App\Infrastructure\Project\Persistence;

use App\Domain\Project\Entities\Project as ProjectEntity;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Shared\Enums\ProjectStatus;
use App\Domain\Shared\Pagination\PaginatedResult;
use App\Infrastructure\Project\Models\Project as ProjectModel;
use App\Infrastructure\Shared\Traits\EntityMapper;

class EloquentProjectRepository implements ProjectRepositoryInterface
{
    use EntityMapper;

    public function findById(string $id): ?ProjectEntity
    {
        $model = ProjectModel::find($id);
        return $model ? $this->mapToEntity($model, ProjectEntity::class) : null;
    }

    public function findBySlug(string $slug): ?ProjectEntity
    {
        $model = ProjectModel::where('slug', $slug)->first();
        return $model ? $this->mapToEntity($model, ProjectEntity::class) : null;
    }

    public function save(ProjectEntity $project): ProjectEntity
    {
        $model = $project->id
            ? ProjectModel::findOrFail($project->id)
            : new ProjectModel();

        $model->fill([
            'owner_id'    => $project->ownerId,
            'name'        => $project->name,
            'slug'        => $project->slug,
            'description' => $project->description,
            'status'      => $project->status->value,
        ])->save();

        return $this->mapToEntity($model->fresh(), ProjectEntity::class);
    }

    public function delete(string $id): void
    {
        ProjectModel::destroy($id);
    }

    public function paginate(array $filters, int $perPage, int $page): PaginatedResult
    {
        $query = ProjectModel::query();

        if (isset($filters['member_id'])) {
            $query->whereHas('members', fn($q) => $q->where('user_id', $filters['member_id']));
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $paginator = $query->orderByDesc('created_at')->paginate($perPage, ['*'], 'page', $page);

        return new PaginatedResult(
            items:       array_map(fn(ProjectModel $m) => $this->mapToEntity($m, ProjectEntity::class), $paginator->items()),
            total:       $paginator->total(),
            perPage:     $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            lastPage:    $paginator->lastPage(),
        );
    }
}
