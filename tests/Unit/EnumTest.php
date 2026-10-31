<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Enum\Icons;
use App\Enum\Severity;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
class EnumTest extends TestCase
{
    public function test_severity_options(): void
    {
        $this->assertSame(['WARNING' => 'warning', 'INFO' => 'info', 'SUCCESS' => 'success'], Severity::options());
    }

    public function test_icons_resolve_from_name_and_invoke_to_path(): void
    {
        $this->assertSame(Icons::SPROUT, Icons::fromName('SPROUT'));
        $this->assertSame('assets/icons/sprout.png', Icons::fromName('SPROUT')());
        $this->assertContains('TOMATO', Icons::names());
    }

    public function test_every_icon_file_exists(): void
    {
        foreach (Icons::cases() as $icon) {
            $this->assertFileExists(dirname(__DIR__, 2).'/public/'.$icon->value);
        }
    }
}
