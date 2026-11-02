<?php

declare(strict_types=1);

namespace Tests\Feature\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

/**
 * Sends real HTTP requests to a running instance of the reading endpoint,
 * e.g. the local DDEV site or production. Excluded from the default run;
 * invoke explicitly with `vendor/bin/phpunit --group smoke`.
 *
 * Configured through environment variables:
 *  - READING_ENDPOINT_URL       Full endpoint URL (required), e.g. https://grow.ddev.site/api/reading
 *  - READING_ENDPOINT_USER      Basic auth email of a user on the target instance
 *  - READING_ENDPOINT_PASSWORD  Basic auth password for that user
 *  - READING_ENDPOINT_NICKNAME  Device nickname; when set a real reading is submitted and processed
 *  - READING_ENDPOINT_READINGS  Optional JSON object of readings, defaults to {"smoke_test": 1}
 */
#[Group('smoke')]
class ReadingEndpointSmokeTest extends TestCase
{
    private string $url;

    protected function setUp(): void
    {
        parent::setUp();

        $url = env('READING_ENDPOINT_URL');

        if (blank($url)) {
            $this->markTestSkipped('READING_ENDPOINT_URL is not set.');
        }

        $this->url = $url;
    }

    public function test_endpoint_rejects_unauthenticated_requests(): void
    {
        $response = $this->client()->post($this->url);

        $this->assertSame(401, $response->status(), $response->body());
    }

    public function test_endpoint_accepts_credentials(): void
    {
        // An empty payload passes authentication but fails validation, so nothing is queued
        $response = $this->authenticatedClient()->post($this->url);

        $this->assertSame(422, $response->status(), $response->body());
        $this->assertEqualsCanonicalizing(['nickname', 'timestamp', 'readings'], array_keys($response->json('errors', [])));
    }

    public function test_endpoint_accepts_reading(): void
    {
        $nickname = env('READING_ENDPOINT_NICKNAME');

        if (blank($nickname)) {
            $this->markTestSkipped('READING_ENDPOINT_NICKNAME is not set; skipping real reading submission.');
        }

        $response = $this->authenticatedClient()->post($this->url, [
            'nickname' => $nickname,
            'timestamp' => now()->toDateTimeString(),
            'readings' => json_decode((string) env('READING_ENDPOINT_READINGS', '{"smoke_test": 1}'), true, flags: JSON_THROW_ON_ERROR),
        ]);

        $this->assertSame(200, $response->status(), $response->body());
        $this->assertSame(['status' => 'OK'], $response->json());
    }

    private function client(): PendingRequest
    {
        return Http::acceptJson()->timeout(15);
    }

    private function authenticatedClient(): PendingRequest
    {
        $user = env('READING_ENDPOINT_USER');
        $password = env('READING_ENDPOINT_PASSWORD');

        if (blank($user) || blank($password)) {
            $this->markTestSkipped('READING_ENDPOINT_USER and READING_ENDPOINT_PASSWORD must be set.');
        }

        return $this->client()->withBasicAuth($user, $password);
    }
}
