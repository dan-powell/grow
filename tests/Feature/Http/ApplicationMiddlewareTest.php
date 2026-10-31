<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('feature')]
class ApplicationMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_forwarded_headers_from_proxies_are_trusted(): void
    {
        $this->get(route('dashboard.show'), [
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Host' => 'grow.example.com',
        ])
            ->assertOk()
            ->assertInertia(fn ($page) => $page->url('/'));

        $this->assertSame('https://grow.example.com', request()->getSchemeAndHttpHost());
    }

    public function test_api_requests_are_rate_limited(): void
    {
        Queue::fake();
        $user = User::factory()->create();

        $this->post(route('reading.store'), [], ['Authorization' => 'Basic '.base64_encode($user->email.':password')])
            ->assertHeader('X-RateLimit-Limit', '60');
    }

    public function test_api_errors_render_as_json(): void
    {
        $this->post('/api/missing')->assertNotFound()->assertHeader('Content-Type', 'application/json');
    }

    public function test_health_check_route(): void
    {
        $this->get('/up')->assertOk();
    }
}
