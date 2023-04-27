<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests;

use JfheinrichEu\LaravelMakeCommands\LaravelMakeCommandsPackageProvider;
use Orchestra\Testbench\TestCase;

class PackageTestCase extends TestCase
{
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
