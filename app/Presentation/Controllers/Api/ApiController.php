<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api;

use App\Presentation\Controllers\Controller;
use App\Presentation\Shared\Traits\ApiResponse;

abstract class ApiController extends Controller
{
    use ApiResponse;
}
