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
        /** @var array<string,mixed> $attributesInput */
        $attributesInput = [
            'id' => 42,
            'name' => 'Hendrix',
            'death_anniversary' => '1970-09-18',
            'age' => 27,
        ];

        $expectedAttributes = collect($attributesInput);

        $expectedArray = [
            'id'         => 42,
            'attributes' => $expectedAttributes,
        ];

        $expectedJson       = json_encode($expectedArray);
        $expectedCollection = collect($expectedArray);

        $dto = new RepositoryDto(
            42,
            collect($attributesInput)
        );

        $array      = $dto->toArray();
        $json       = json_encode($dto);
        $collection = $dto->toCollection();

        self::assertInstanceOf(DataTransferObject::class, $dto, 'Not a instance of DataTransferObject::class');
        self::assertEquals($expectedArray, $array, 'toArray() failed');
        self::assertEquals($expectedJson, $json, 'toJson failed');
        self::assertEquals($expectedCollection, $collection, 'toCollection failed');

        self::assertEquals(42, $dto->id, 'ID: Wrong id returned');
        self::assertEquals($expectedAttributes, $dto->attributes, 'Attributes: Not the same collection returned');
        $dto->id = 69;
        self::assertEquals(69, $dto->id, 'Can\t override property id');
    }
}
