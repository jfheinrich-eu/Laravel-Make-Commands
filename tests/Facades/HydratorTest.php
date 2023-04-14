<?php

declare(strict_types=1);

use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;
use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;

test('Hydrate Test::class', function (string $name, string $studio) {
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
})->with('data');

test('creates our data transfer object as we would expect', function (string $name, string $studio) {
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
})->with('data');
