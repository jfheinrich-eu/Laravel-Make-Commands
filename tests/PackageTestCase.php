<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\LaravelMakeCommandsPackageProvider;
use Orchestra\Testbench\TestCase;

class PackageTestCase extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan(
            'migrate',
            [
                '--database' => 'testbench',
            ]
        )->run();
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

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('make_commands.useview.namespaces', [
            'JfheinrichEu\\LaravelMakeCommands\\Tests\Stubs\\Models\\',
        ]);

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
