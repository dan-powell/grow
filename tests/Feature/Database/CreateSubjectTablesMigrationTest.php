<?php

declare(strict_types=1);

namespace Tests\Feature\Database;

use App\Models\Datum;
use App\Models\Device;
use App\Models\Figure;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class CreateSubjectTablesMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_figures_get_a_subject_assignment_and_their_alerts(): void
    {
        $this->artisan('migrate:rollback', ['--step' => 1])->assertSuccessful();
        $figure = Figure::factory()->for(Device::factory())->create(['name' => 'Basil', 'summary' => 'Kitchen window']);
        Datum::factory()->create(['figure_id' => $figure->id, 'timestamp' => '2024-06-02 00:00:00']);
        Datum::factory()->create(['figure_id' => $figure->id, 'timestamp' => '2024-06-01 08:00:00']);
        $alertId = (string) Str::ulid();
        DB::table('figure_alert')->insert(['id' => $alertId, 'figure_id' => $figure->id, 'enabled' => true, 'value' => 30, 'lower' => true, 'email' => true, 'activated' => '2024-06-02 00:00:00']);

        $this->artisan('migrate')->assertSuccessful();

        $subject = Subject::sole();
        $this->assertSame('Basil', $subject->name);
        $this->assertSame('Kitchen window', $subject->summary);
        $assignment = $figure->currentAssignment()->sole();
        $this->assertSame($subject->id, $assignment->subject_id);
        $this->assertSame('2024-06-01 08:00:00', $assignment->started_at->toDateTimeString());
        $alert = $subject->alerts()->sole();
        $this->assertSame($alertId, $alert->id);
        $this->assertEqualsWithDelta(30.0, $alert->value, 0.0001);
        $this->assertTrue((bool) $alert->lower);
        $this->assertSame('2024-06-02 00:00:00', $alert->activated->toDateTimeString());
        $this->assertFalse(DB::getSchemaBuilder()->hasTable('figure_alert'));
    }
}
