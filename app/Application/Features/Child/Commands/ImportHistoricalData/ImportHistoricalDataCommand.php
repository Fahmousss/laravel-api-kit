<?php

declare(strict_types=1);

namespace App\Application\Features\Child\Commands\ImportHistoricalData;

final class ImportHistoricalDataCommand
{
    public function __construct(
        public readonly string $filePath,
        public readonly string $posyanduId,
    ) {}
}
