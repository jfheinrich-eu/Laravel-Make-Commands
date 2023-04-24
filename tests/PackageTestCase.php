<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests;

use Orchestra\Testbench\TestCase;
use JfheinrichEu\LaravelMakeCommands\LaravelMakeCommandsPackageProvider;

class PackageTestCase extends TestCase
{
    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    protected $enablesPackageDiscoveries = true;

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
}
