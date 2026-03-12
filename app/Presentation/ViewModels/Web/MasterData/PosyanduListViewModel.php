<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels\Web\MasterData;

use App\Application\Features\Posyandus\DTOs\PosyanduDTO;
use App\Domain\Shared\Pagination\PaginatedResult;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Date;

final readonly class PosyanduListViewModel
{
    /**
     * @var PosyanduViewModel[]
     */
    public array $posyandus;

    public LengthAwarePaginator $paginator;

    public function __construct(PaginatedResult $paginatedResult)
    {
        $this->posyandus = array_map(
            static fn (PosyanduDTO $dto): PosyanduViewModel => new PosyanduViewModel($dto),
            $paginatedResult->items
        );

        $this->paginator = new LengthAwarePaginator(
            items: $this->posyandus,
            total: $paginatedResult->total,
            perPage: $paginatedResult->perPage,
            currentPage: $paginatedResult->currentPage,
            options: ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }
}

final readonly class PosyanduViewModel
{
    public int $id;

    public string $name;

    public string $district;

    public string $location;

    public ?float $lat;

    public ?float $lng;

    public string $createdAtFormatted;

    public function __construct(PosyanduDTO $dto)
    {
        $this->id                 = $dto->id;
        $this->name               = $dto->name;
        $this->district           = $dto->district;
        $this->location           = $dto->location ?? '-';
        $this->lat                = $dto->lat;
        $this->lng                = $dto->lng;
        $this->createdAtFormatted = Date::parse($dto->createdAt)->translatedFormat('d F Y');
    }
}
