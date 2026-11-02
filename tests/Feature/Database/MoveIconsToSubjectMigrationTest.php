<?php

declare(strict_types=1);

namespace Tests\Feature\Database;

use App\Models\Device;
use App\Models\Figure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class MoveIconsToSubjectMigrationTest extends TestCase
{
    use RefreshDatabase;

    private const MIGRATION = 'database/migrations/2026_11_02_110000_move_icons_to_subject.php';

    public function test_main_icon_moves_to_the_current_subject_and_badge_becomes_the_figure_icon(): void
    {
        $this->artisan('migrate:rollback', ['--path' => self::MIGRATION])->assertSuccessful();
        $device = Device::factory()->create();
        $assigned = Figure::factory()->for($device)->create(['icon' => 'BASIL', 'icon_custom' => 'basil.png']);
        $unassigned = Figure::factory()->for($device)->create(['icon' => 'TOMATO']);
        DB::table('figure')->where('id', $assigned->id)->update(['icon_small' => 'MOISTURE']);
        $subjectId = (string) Str::ulid();
        DB::table('subject')->insert(['id' => $subjectId, 'name' => 'Basil']);
        DB::table('figure_assignment')->insert(['id' => (string) Str::ulid(), 'figure_id' => $assigned->id, 'subject_id' => $subjectId, 'started_at' => '2024-06-01 00:00:00']);

        $this->artisan('migrate')->assertSuccessful();

        $this->assertDatabaseHas('subject', ['id' => $subjectId, 'icon' => 'BASIL', 'icon_custom' => 'basil.png']);
        $this->assertDatabaseHas('figure', ['id' => $assigned->id, 'icon' => 'MOISTURE', 'icon_custom' => null]);
        $this->assertDatabaseHas('figure', ['id' => $unassigned->id, 'icon' => 'TOMATO']);
    }
}
