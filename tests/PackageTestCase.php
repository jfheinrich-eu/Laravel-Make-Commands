<?php

declare(strict_types=1);

namespace Jfheinrich\DataObjects\Tests;

use Jfheinrich\DataObjects\Providers\PackageServiceProvider;
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
            PackageServiceProvider::class,
        ];
    }
}
