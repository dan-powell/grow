<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\LogHelperService;
use Illuminate\Support\Facades\Facade;

class LogHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LogHelperService::class;
    }
}
