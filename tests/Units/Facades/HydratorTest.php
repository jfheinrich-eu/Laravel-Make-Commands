<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Facades;

use ReflectionClass;
use Illuminate\Support\Str;
use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;

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
            class: Test::class,
            properties: ['name' => $name, 'studio' => $studio],
        );

        $array = $dto->toArray();
        $json = json_encode($dto);
        $collection = $dto->toCollection();

        expect($dto)->toBeInstanceOf(Test::class, 'Not a instance of Test::class');
        expect($dto)->toBeInstanceOf(DataTransferObject::class, 'Not a instance of DataTransferObject::class');
        expect($array)->toEqual(['name' => $name, 'studio' => $studio], 'toArray() failed');
        expect($json)->toEqual("{\"name\":\"$name\",\"studio\":\"$studio\"}", 'toJson failed');
        expect($collection)->toEqual(collect(['name' => $name, 'studio' => $studio]), 'toCollection failed');
        expect($dto->name)->toEqual($name, 'Can\'t access property "name"');
        expect($dto->studio)->toEqual($studio, 'Can\'t access property "studio"');
        $dto->studio = 'override';
        expect($dto->studio)->toEqual('override', 'Can\'t write property "studio"');
        expect($dto->unknown)->toBeNull('Access to unknown property, don\'t return Null.');
    }

    /**
     *
     * @dataProvider data_provider
     * @param string $name
     * @param string $studio
     * @return void
     */
    public function  test_creates_our_data_transfer_object_as_we_would_expect(string $name, string $studio): void
    {
        $test = Hydrator::fill(
            class: Test::class,
            properties: ['name' => $name, 'studio' => $studio],
        );

        $reflection = new ReflectionClass(
            objectOrClass: $test,
        );

        expect(
            $reflection->getProperty(
                name: 'name',
            )->isReadOnly()
        )->toBeTrue()->and(
            $reflection->getProperty(
                name: 'name',
            )->isProtected(),
        )->toBeTrue()->and(
            $reflection->getProperty(
                name: 'studio',
            )->isReadOnly(),
        )->toBeFalse()->and(
            $reflection->getProperty(
                name: 'studio',
            )->isProtected(),
        )->toBeTrue()->and(
            $reflection->getMethod(
                name: 'toArray',
            )->hasReturnType(),
        )->toBeTrue()->and(
            $reflection->getMethod(
                name: 'JsonSerialize',
            )->hasReturnType(),
        )->toBeTrue()->and(
            $reflection->getMethod(
                name: 'toCollection',
            )->hasReturnType(),
        )->toBeTrue();
    }

    // Provider

    public function hydrator_provider(): array
    {
        return [
            ['Jimi Hendrix', 'Electric Lady Studios'],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
            [Str::random(), Str::random()],
        ];
    }

    public function data_provider(): array
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
