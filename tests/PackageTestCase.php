<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests;

use Orchestra\Testbench\TestCase;
use JfheinrichEu\LaravelMakeCommands\Providers\PackageServiceProvider;

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
            PackageServiceProvider::class,
        ];
    }
}
