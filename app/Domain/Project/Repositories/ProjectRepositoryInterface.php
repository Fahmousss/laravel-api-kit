<?php

declare(strict_types=1);

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\Project;
use App\Domain\Shared\Pagination\PaginatedResult;

interface ProjectRepositoryInterface
{
    public function findById(string $id): ?Project;

    public function findBySlug(string $slug): ?Project;

    public function save(Project $project): Project;

    public function delete(string $id): void;

    /**
     * @return PaginatedResult<Project>
     */
    public function paginate(array $filters, int $perPage, int $page): PaginatedResult;
}
