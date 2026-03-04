<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Commands\LogMeasurement;

use App\Domain\Child\Enums\StuntingStatus;
use App\Domain\Child\Repositories\ChildRepositoryInterface;
use App\Domain\Child\ValueObjects\Height;
use App\Domain\Child\ValueObjects\Weight;
use App\Infrastructure\Child\Models\Measurement;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class LogMeasurementCommandHandler
{
    public function __construct(
        private readonly ChildRepositoryInterface $repository
    ) {}

    public function handle(LogMeasurementCommand $command): Measurement
    {
        $child = $this->repository->findById($command->childId);

        if (! $child) {
            throw new InvalidArgumentException('Child not found.');
        }

        $height = new Height($command->heightCm);
        $weight = new Weight($command->weightKg);

        // Simple WHO calculation approximation for the prototype
        $status = StuntingStatus::NORMAL;
        if ($height->value() < 80) { // Arbitrary threshold for demo
            $status = StuntingStatus::STUNTED;
        }

        $measurement = Measurement::create([
            'id'              => (string) Str::uuid(),
            'child_id'        => $child->id,
            'measured_at'     => $command->measuredAt,
            'height_cm'       => $height->value(),
            'weight_kg'       => $weight->value(),
            'stunting_status' => $status->value,
        ]);

        return $measurement;
    }
}
