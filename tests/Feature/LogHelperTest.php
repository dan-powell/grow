<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enum\Severity;
use App\Facades\LogHelper;
use App\Models\Device;
use App\Models\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class LogHelperTest extends TestCase
{
    use RefreshDatabase;

    public function test_builds_and_saves_a_log_entry(): void
    {
        $device = Device::factory()->create();

        LogHelper::warning()->name('Something')->summary('Short')->description('Long')->device($device)->save();

        $log = Log::sole();
        $this->assertSame('Something', $log->name);
        $this->assertSame('Short', $log->summary);
        $this->assertSame('Long', $log->description);
        $this->assertSame(Severity::WARNING->value, $log->severity);
        $this->assertSame($device->id, $log->device_id);
    }

    public function test_resets_between_entries(): void
    {
        LogHelper::success()->name('First')->summary('One')->save();
        LogHelper::name('Second')->save();

        $second = Log::where('name', 'Second')->sole();
        $this->assertNull($second->summary);
        $this->assertNull($second->severity);
        $this->assertSame(2, Log::count());
    }

    public function test_create_shortcut(): void
    {
        LogHelper::create('Created', 'Summary');

        $this->assertDatabaseHas('log', ['name' => 'Created', 'summary' => 'Summary']);
    }
}
