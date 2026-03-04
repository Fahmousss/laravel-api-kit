<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Commands\ImportHistoricalData;

use App\Application\Features\Child\Commands\LogMeasurement\LogMeasurementCommand;
use App\Application\Features\Child\Commands\LogMeasurement\LogMeasurementCommandHandler;
use App\Application\Features\Child\Commands\RegisterChild\RegisterChildCommand;
use App\Application\Features\Child\Commands\RegisterChild\RegisterChildCommandHandler;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelReader;

final class ImportHistoricalDataCommandHandler
{
    public function __construct(
        private readonly RegisterChildCommandHandler $registerHandler,
        private readonly LogMeasurementCommandHandler $logHandler
    ) {}

    public function handle(ImportHistoricalDataCommand $command): void
    {
        SimpleExcelReader::create($command->filePath)
            ->getRows()
            ->each(function (array $rowProperties) use ($command) {
                try {
                    $registerCommand = new RegisterChildCommand(
                        posyanduId: $command->posyanduId,
                        name: $rowProperties['Name'] ?? 'Unknown',
                        nik: $rowProperties['NIK'] ?? null,
                        dateOfBirth: $rowProperties['DOB'] ?? now()->toDateString(),
                        gender: mb_strtolower($rowProperties['Gender'] ?? 'male'),
                    );

                    $child = $this->registerHandler->handle($registerCommand);

                    $logCommand = new LogMeasurementCommand(
                        childId: $child->id,
                        heightCm: (float) ($rowProperties['Height'] ?? 0),
                        weightKg: (float) ($rowProperties['Weight'] ?? 0),
                        measuredAt: $rowProperties['DateRecorded'] ?? now()->toDateString(),
                    );

                    $this->logHandler->handle($logCommand);

                } catch (Exception $e) {
                    Log::error('Failed to import child record: '.$e->getMessage(), ['row' => $rowProperties]);
                }
            });
    }
}
