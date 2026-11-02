<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\AccountWidget as BaseAccountWidget;

/**
 * Filament's welcome widget, spanning the full width of the dashboard.
 */
class AccountWidget extends BaseAccountWidget
{
    #[\Override]
    protected int|string|array $columnSpan = 'full';
}
