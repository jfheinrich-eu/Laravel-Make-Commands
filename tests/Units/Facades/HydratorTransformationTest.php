<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Facades;

use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;

final class HydratorTransformationTest extends PackageTestCase
{
    public function test_hydrate_test_class_check_transformations(): void
    {
        $name = 'Jimi Hendirx';
        $studio = 'Electric Lady Studios';

        $dto = Hydrator::fill(
            Test::class,
            ['name' => $name, 'studio' => $studio],
        );

        $array = $dto->toArray();
        $json = json_encode($dto);
        $collection = $dto->toCollection();

        self::assertEquals(['name' => $name, 'studio' => $studio], $array, 'toArray() failed');
        self::assertEquals("{\"name\":\"$name\",\"studio\":\"$studio\"}", $json, 'toJson failed');
        self::assertEquals(collect(['name' => $name, 'studio' => $studio]), $collection, 'toCollection failed');
    }
}
