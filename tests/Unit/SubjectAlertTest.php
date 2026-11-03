<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\SubjectAlert;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
class SubjectAlertTest extends TestCase
{
    public function test_upper_alert_trips_above_and_resolves_below_the_margin(): void
    {
        $alert = new SubjectAlert(['value' => 30, 'lower' => false, 'resolve_margin' => 2]);

        $this->assertTrue($alert->isTrippedBy(30.1));
        $this->assertFalse($alert->isTrippedBy(30));
        $this->assertFalse($alert->isResolvedBy(29));
        $this->assertTrue($alert->isResolvedBy(27.9));
    }

    public function test_lower_alert_trips_below_and_resolves_above_the_margin(): void
    {
        $alert = new SubjectAlert(['value' => 30, 'lower' => true, 'resolve_margin' => 2]);

        $this->assertTrue($alert->isTrippedBy(29.9));
        $this->assertFalse($alert->isTrippedBy(30));
        $this->assertFalse($alert->isResolvedBy(31));
        $this->assertTrue($alert->isResolvedBy(32.1));
    }

    public function test_alert_without_a_value_never_trips_or_resolves(): void
    {
        $alert = new SubjectAlert(['value' => null, 'lower' => false]);

        $this->assertFalse($alert->isTrippedBy(100));
        $this->assertFalse($alert->isResolvedBy(-100));
    }

    public function test_breach_summary_rounds_the_difference(): void
    {
        $this->assertSame(
            'The reading value "28.7" has subceeded the alert by 1.3',
            new SubjectAlert(['value' => 30, 'lower' => true])->breachSummary(28.7),
        );
    }
}
