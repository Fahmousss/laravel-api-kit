<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Traits;

use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

trait EntityMapper
{
    /**
     * Dynamically map an Eloquent model to a domain entity.
     *
     * @template T of object
     *
     * @param class-string<T> $entityClass
     *
     * @return T
     */
    protected function mapToEntity(Model $model, string $entityClass): object
    {
        $reflection  = new ReflectionClass($entityClass);
        $constructor = $reflection->getConstructor();

        // If the entity has no constructor, instantiate directly
        if (! $constructor) {
            return new $entityClass();
        }

        $args = [];
        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();

            // Allow matching Eloquent attributes (including mutators and casts)
            $args[$name] = $model->getAttribute($name);
        }

        return $reflection->newInstanceArgs($args);
    }
}
