<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Facades;

use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;
use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;

final class HydratorTest extends PackageTestCase
{
    public function test_hydrate_test_class_check_instance_of(): void
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

        self::assertInstanceOf(Test::class, $dto, 'Not a instance of Test::class');
        self::assertInstanceOf(DataTransferObject::class, $dto, 'Not a instance of DataTransferObject::class');
    }
}
