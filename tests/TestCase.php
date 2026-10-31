<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Built assets aren't available when running tests
        $this->withoutVite();

        // Inertia pages live in resources/inertia rather than the default resources/js
        config(['inertia.testing.page_paths' => [resource_path('inertia/Pages')]]);
    }
}
