<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Facades;

use Illuminate\Support\Str;
use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;
use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;
use ReflectionClass;

final class HydratorTest extends PackageTestCase
{
    /**
     *
     * @dataProvider hydrator_provider
     * @param string $name
     * @param string $studio
     * @return void
     */
    public function test_hydrate_test_class(string $name, string $studio): void
    {
        $dto = Hydrator::fill(
            Test::class,
            ['name' => $name, 'studio' => $studio],
        );

        $array = $dto->toArray();
        $json = json_encode($dto);
        $collection = $dto->toCollection();

        self::assertInstanceOf(Test::class, $dto, 'Not a instance of Test::class');
        self::assertInstanceOf(DataTransferObject::class, $dto, 'Not a instance of DataTransferObject::class');
        self::assertEquals([ 'name' => $name, 'studio' => $studio ], $array, 'toArray() failed');
        self::assertEquals("{\"name\":\"$name\",\"studio\":\"$studio\"}", $json, 'toJson failed');
        self::assertEquals(collect([ 'name' => $name, 'studio' => $studio ]), $collection, 'toCollection failed');
        self::assertEquals($name, $dto->name, 'Can\'t access property "name"');
        self::assertEquals($studio, $dto->studio, 'Can\'t access property "studio"');
        $dto->studio = 'override';
        self::assertEquals('override', $dto->studio, 'Can\'t write property "studio"');
        self::assertNull($dto->unknown, 'Access to unknown property, don\'t return Null.');
    }

    /**
     *
     * @dataProvider data_provider
     * @param string $name
     * @param string $studio
     * @return void
     */
    public function test_creates_our_data_transfer_object_as_we_would_expect(string $name, string $studio): void
    {
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

    // Provider

    public static function hydrator_provider(): array
    {
        return [
            ['Jimi Hendrix', 'Electric Lady Studios'],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
        ];
    }

    public static function data_provider(): array
    {
        return [
            ['Jimi Hendrix', 'Electric Lady Studios'],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
        ];
    }
}
