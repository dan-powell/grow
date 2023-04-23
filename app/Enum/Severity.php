<?php

namespace App\Enum;

use ArchTech\Enums\{From, InvokableCases, Names, Options};

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
