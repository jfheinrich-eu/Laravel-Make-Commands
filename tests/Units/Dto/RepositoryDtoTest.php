<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Dto;

use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;
use JfheinrichEu\LaravelMakeCommands\Dto\RepositoryDto;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;

final class RepositoryDtoTest extends PackageTestCase
{
    public function test_create_repository_dto(): void
    {
        $expectedAttributes = collect([
                'hendrix' => [
                    'id'                => 42,
                    'name'              => 'Hendrix',
                    'death_anniversary' => '1970-09-18',
                    'age'               => 27,
                ]
            ]);

        $expectedArray = [
            'id'         => 42,
            'attributes' => $expectedAttributes,
        ];

        $expectedJson       = json_encode($expectedArray);
        $expectedCollection = collect($expectedArray);

        $dto = new RepositoryDto(
            42,
            collect([
                'hendrix' => [
                    'id'                => 42,
                    'name'              => 'Hendrix',
                    'death_anniversary' => '1970-09-18',
                    'age'               => 27,
                ]
            ]),
        );

        $array      = $dto->toArray();
        $json       = json_encode($dto);
        $collection = $dto->toCollection();

        expect($dto)->toBeInstanceOf(DataTransferObject::class, 'Not a instance of DataTransferObject::class');
        expect($array)->toEqual($expectedArray, 'toArray() failed');
        expect($json)->toEqual($expectedJson, 'toJson failed');
        expect($collection)->toEqual($expectedCollection, 'toCollection failed');

        expect($dto->id)->toEqual(42, 'ID: Wrong id returned');
        expect($dto->attributes)->toEqual($expectedAttributes, 'Attributes: Not the same collection returned');
        $dto->id = 69;
        expect($dto->id)->toEqual(69, 'Can\t override property id');
    }
}
