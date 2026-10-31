<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Filament\Resources\DeviceResource;
use App\Filament\Resources\FigureAlertResource;
use App\Filament\Resources\FiguresResource;
use App\Filament\Resources\LogResource;
use App\Filament\Resources\UserResource;
use App\Models\Device;
use App\Models\Figure;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'figure alerts' => [FigureAlertResource::class],
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
}
