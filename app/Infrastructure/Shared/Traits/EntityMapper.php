<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionClass;

trait EntityMapper
{
    /**
     * Dynamically map an Eloquent model or collection to domain entities.
     *
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return array<T>|T
     */
    protected function mapToEntity(Model|Collection $model, string $entityClass): object|array
{
    if ($model instanceof Collection) {
        return $model->map(fn (Model $item) => $this->mapToEntity($item, $entityClass))->all();
    }

    $reflection  = new ReflectionClass($entityClass);
    $constructor = $reflection->getConstructor();

    if (! $constructor) {
        return new $entityClass();
    }

    $args = [];

    foreach ($constructor->getParameters() as $parameter) {
        $name          = $parameter->getName();
        $attributeName = Str::snake($name);

        $value = $model->getAttribute($attributeName)
            ?? $model->getAttribute($name);

        if ($value === null && $model->relationLoaded($name)) {
            $relation = $model->getRelation($name);

            if ($relation instanceof Model) {
                $value = $relation->toArray();
            } elseif ($relation instanceof Collection) {
                $value = $relation->map->toArray()->all();
            }
        }

        $args[$name] = $value;
    }

    return $reflection->newInstanceArgs($args);
}
}
