<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Facades;

use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;
use ReflectionClass;

final class HydratorCreateAsExpectedTest extends PackageTestCase
{
    public function test_creates_our_data_transfer_object_as_we_would_expect(): void
    {
        $name = 'Jimi Hendirx';
        $studio = 'Electric Lady Studios';

        /** @var Test $test */
        $test = Hydrator::fill(
            Test::class,
            ['name' => $name, 'studio' => $studio],
        );

        $reflection = new ReflectionClass(
            objectOrClass: $test,
        );

        self::assertObjectHasProperty('name', $test, 'Property name not found');
        self::assertObjectHasProperty('studio', $test, 'Property studio not found');
        self::assertEquals($name, $test->name, 'Property name has not the expected value');
        self::assertEquals($studio, $test->studio, 'Property studio has not the expected value');
    }
}
