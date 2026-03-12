<?php

declare(strict_types=1);

namespace App\Presentation\ViewModels\Web\MasterData;

use App\Application\Features\Children\DTOs\ChildDTO;
use Illuminate\Support\Facades\Date;

final readonly class ChildListViewModel
{
    /**
     * @var ChildViewModel[]
     */
    public array $children;

    /**
     * @param ChildDTO[] $dtos
     */
    public function __construct(array $dtos)
    {
        $this->children = array_map(
            static fn (ChildDTO $dto): ChildViewModel => new ChildViewModel($dto),
            $dtos
        );
    }
}

final readonly class ChildViewModel
{
    public int $id;

    public int $posyanduId;

    public string $nik;

    public string $name;

    public string $dobFormatted;

    public string $gender;

    public string $parentName;

    public string $ageString;

    public function __construct(ChildDTO $dto)
    {
        $this->id           = $dto->id;
        $this->posyanduId   = $dto->posyanduId;
        $this->nik          = $dto->nik;
        $this->name         = $dto->name;
        $this->dobFormatted = Date::parse($dto->dob)->translatedFormat('d F Y');
        $this->gender       = $dto->gender === 'M' ? 'Male' : 'Female';
        $this->parentName   = $dto->parentName;

        $dob             = Date::parse($dto->dob);
        $months          = $dob->diffInMonths(now());
        $this->ageString = $months.' Bulan';
    }
}
