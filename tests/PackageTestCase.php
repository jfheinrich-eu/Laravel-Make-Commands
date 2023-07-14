<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use JfheinrichEu\LaravelMakeCommands\LaravelMakeCommandsPackageProvider;
use Orchestra\Testbench\TestCase;

class PackageTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param $app
     * @return array<int,class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelMakeCommandsPackageProvider::class,
        ];
    }

    // protected function defineDatabaseMigrations(): void
    // {
    //     $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    // }
}
