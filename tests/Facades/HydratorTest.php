<?php

declare(strict_types=1);

use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;

it('can create a data transfer object and get array', function (string $string) {
    expect(
        Hydrator::fill(
            class: Test::class,
            properties: ['name' => $string],
        ),
    )->toBeInstanceOf(Test::class)->toArray()->toEqual(['name' => $string]);
})->with('strings');

it('can create a data transfer object and get collection', function (string $string) {
    expect(
        Hydrator::fill(
            class: Test::class,
            properties: ['name' => $string],
        ),
    )->toBeInstanceOf(Test::class)->toCollection()->toEqual(collect(['name' => $string]));
})->with('strings');

it('can create a data transfer object and get json', function (string $string) {
    expect(
        $json = json_encode(Hydrator::fill(
            class: Test::class,
            properties: ['name' => $string],
        )),
    )->toBeJson()->toEqual(json_encode(['name' => $string]));
})->with('strings');

it('creates our data transfer object as we would expect', function (string $string) {
    $test = Hydrator::fill(
        class: Test::class,
        properties: ['name' => $string],
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
        )->isPrivate(),
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
})->with('strings');
