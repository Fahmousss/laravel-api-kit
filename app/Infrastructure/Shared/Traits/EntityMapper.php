<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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
            $name          = $parameter->getName();
            $attributeName = Str::snake($name);

            // Allow matching Eloquent attributes (including mutators and casts)
            // Eloquent attributes are typically snake_case
            $value = $model->getAttribute($attributeName) ?? $model->getAttribute($name);

            // Allow an implementing class to hook into and override attribute mapping
            if (method_exists($this, 'mapCustomAttribute')) {
                $value = $this->mapCustomAttribute($model, $name, $value);
            }

            $args[$name] = $value;
        }

        return $reflection->newInstanceArgs($args);
    }
}
