<?php

namespace App\Enum;

use ArchTech\Enums\From;
use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;

enum Severity: string
{
    use From;
    use InvokableCases;
    use Names;
    use Options;

    case WARNING = 'warning';
    case INFO = 'info';
    case SUCCESS = 'success';
}
