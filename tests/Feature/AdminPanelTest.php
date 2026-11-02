<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\DeviceResource;
use App\Filament\Resources\FiguresResource;
use App\Filament\Resources\FiguresResource\Pages\EditFigures;
use App\Filament\Resources\FiguresResource\RelationManagers\AssignmentsRelationManager;
use App\Filament\Resources\LogResource;
use App\Filament\Resources\SubjectResource;
use App\Filament\Resources\SubjectResource\Pages\EditSubject;
use App\Filament\Resources\SubjectResource\RelationManagers\AssignmentsRelationManager as SubjectAssignmentsRelationManager;
use App\Filament\Resources\UserResource;
use App\Models\Device;
use App\Models\Figure;
use App\Models\FigureAssignment;
use App\Models\Subject;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();
    }

    public function test_non_admins_are_forbidden(): void
    {
        $this->actingAs(User::factory()->create(['admin' => false]))
            ->get('/admin')
            ->assertForbidden();
    }

    public static function resourceProvider(): array
    {
        return [
            'devices' => [DeviceResource::class],
            'figures' => [FiguresResource::class],
            'subjects' => [SubjectResource::class],
            'logs' => [LogResource::class],
            'users' => [UserResource::class],
        ];
    }

    #[DataProvider('resourceProvider')]
    public function test_admins_can_view_resource_pages(string $resource): void
    {
        Device::factory()->has(Figure::factory(), 'figures')->create();

        $this->actingAs(User::factory()->create(['admin' => true]))
            ->get('/admin')
            ->assertOk();

        $this->actingAs(User::factory()->create(['admin' => true]))
            ->get($resource::getUrl('index'))
            ->assertOk();

        if ($resource::hasPage('create')) {
            $this->get($resource::getUrl('create'))->assertOk();
        }
    }

    public function test_user_menu_includes_view_site_link(): void
    {
        $this->actingAs(User::factory()->create(['admin' => true]))
            ->get('/admin')
            ->assertOk()
            ->assertSeeHtml('fi-dropdown-list-item-label">View</span>');
    }

    public function test_admins_can_edit_a_device(): void
    {
        $device = Device::factory()->has(Figure::factory(), 'figures')->create();

        $this->actingAs(User::factory()->create(['admin' => true]))
            ->get(DeviceResource::getUrl('edit', ['record' => $device]))
            ->assertOk()
            ->assertSee($device->name);
    }

    public function test_figures_index_links_to_the_assigned_subject_and_device(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        $subject = FigureAssignment::factory()->for($figure)->create()->subject;

        $this->actingAs(User::factory()->create(['admin' => true]))
            ->get(FiguresResource::getUrl('index'))
            ->assertOk()
            ->assertSee(SubjectResource::getUrl('edit', ['record' => $subject]))
            ->assertSee(DeviceResource::getUrl('edit', ['record' => $figure->device]));
    }

    public function test_subjects_index_links_to_the_assigned_figure_and_device(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        FigureAssignment::factory()->for($figure)->create();

        $this->actingAs(User::factory()->create(['admin' => true]))
            ->get(SubjectResource::getUrl('index'))
            ->assertOk()
            ->assertSee(FiguresResource::getUrl('edit', ['record' => $figure]))
            ->assertSee(DeviceResource::getUrl('edit', ['record' => $figure->device]));
    }

    public function test_subject_figures_table_links_to_the_figure(): void
    {
        $assignment = FigureAssignment::factory()->for(Figure::factory()->for(Device::factory()))->create();

        $this->actingAs(User::factory()->create(['admin' => true]));
        Livewire::test(SubjectAssignmentsRelationManager::class, ['ownerRecord' => $assignment->subject, 'pageClass' => EditSubject::class])
            ->assertSee(FiguresResource::getUrl('edit', ['record' => $assignment->figure]));
    }

    public function test_admins_can_assign_a_subject_from_the_figure(): void
    {
        $this->freezeSecond();
        $figure = Figure::factory()->for(Device::factory())->create();
        $previous = FigureAssignment::factory()->for($figure)->create();
        $subject = Subject::factory()->create();

        $this->actingAs(User::factory()->create(['admin' => true]));
        Livewire::test(AssignmentsRelationManager::class, ['ownerRecord' => $figure, 'pageClass' => EditFigures::class])
            ->callAction(TestAction::make('assignSubject')->table(), ['subject_id' => $subject->id, 'started_at' => now()->toDateTimeString()])
            ->assertHasNoFormErrors()
            ->assertNotified('Subject assigned');

        $this->assertTrue($previous->fresh()->ended_at->equalTo(now()));
        $this->assertSame($subject->id, $figure->currentSubject->id);
    }

    public function test_editing_an_assignment_rejects_an_overlapping_period(): void
    {
        $figure = Figure::factory()->for(Device::factory())->create();
        FigureAssignment::factory()->for($figure)->create(['started_at' => '2024-06-01 00:00:00', 'ended_at' => '2024-06-10 00:00:00']);
        $assignment = FigureAssignment::factory()->for($figure)->create(['started_at' => '2024-06-10 00:00:00']);

        $this->actingAs(User::factory()->create(['admin' => true]));
        Livewire::test(AssignmentsRelationManager::class, ['ownerRecord' => $figure, 'pageClass' => EditFigures::class])
            ->callAction(TestAction::make('edit')->table($assignment), ['started_at' => '2024-06-05 00:00:00'])
            ->assertHasFormErrors(['started_at']);

        $this->assertSame('2024-06-10 00:00:00', $assignment->fresh()->started_at->toDateTimeString());
    }
}
