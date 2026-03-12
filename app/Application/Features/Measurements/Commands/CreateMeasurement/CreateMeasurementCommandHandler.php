<?php

declare(strict_types=1);

namespace App\Application\Features\Measurements\Commands\CreateMeasurement;

use App\Application\Features\Measurements\DTOs\MeasurementDTO;
use App\Domain\Children\Entities\ChildEntity;
use App\Domain\Children\Exceptions\ChildrenNotFoundException;
use App\Domain\Children\Repositories\ChildRepositoryInterface;
use App\Domain\Measurements\Entities\MeasurementEntity;
use App\Domain\Measurements\Repositories\MeasurementRepositoryInterface;
use App\Domain\Measurements\Services\StuntingCalculatorServiceInterface;
use Illuminate\Support\Facades\Date;

final readonly class CreateMeasurementCommandHandler
{
    public function __construct(
        private MeasurementRepositoryInterface $measurementRepository,
        private ChildRepositoryInterface $childRepository,
        private StuntingCalculatorServiceInterface $calculatorService,
    ) {}

    public function handle(CreateMeasurementCommand $command): MeasurementDTO
    {
        // Fetch Child for Gender and DOB
        $child = $this->childRepository->findById($command->childId);

        throw_if(! $child instanceof ChildEntity, ChildrenNotFoundException::class, 'Child not found for calculation.');

        $dob = Date::parse($child->dob);

        // Calculate Z-Score and Stunting Status
        $evaluation = $this->calculatorService->evaluate(
            heightCm: $command->heightCm,
            position: $command->position,
            gender: $child->gender,
            dob: $dob,
            measurementDate: $command->measurementDate,
        );

        $entity = MeasurementEntity::create(
            childId: $command->childId,
            date: $command->measurementDate->toDateString(),
            height: $command->heightCm,
            position: $command->position,
            weight: $command->weightKg,
            lat: $command->lat,
            lng: $command->lng,
            zScore: $evaluation['z_score'],
            status: $evaluation['status']
        );

        $savedEntity = $this->measurementRepository->create($entity);

        return new MeasurementDTO(
            id: $savedEntity->id,
            childId: $savedEntity->childId,
            date: $savedEntity->date,
            height: $savedEntity->height,
            weight: $savedEntity->weight,
            lat: $savedEntity->lat,
            lng: $savedEntity->lng,
            zScore: $savedEntity->zScore,
            status: $savedEntity->status,
            createdAt: $savedEntity->createdAt ?? now()->toIso8601String(),
        );
    }
}
