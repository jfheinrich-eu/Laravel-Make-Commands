<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Facades;

use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\TestPropertyAccess;

final class HydratorPropertyAccessTest extends PackageTestCase
{
    public function test_hydrate_test_class_check_property_access(): void
    {
        $name = 'Jimi Hendirx';
        $studio = 'Electric Lady Studios';
        $enabled = true;

        /** @var TestPropertyAccess $dto */
        $dto = Hydrator::fill(
            TestPropertyAccess::class,
            ['name' => $name, 'studio' => $studio, 'enabled' => $enabled],
        );

        self::assertEquals($name, $dto->name, 'Can\'t access property "name"');
        self::assertEquals($studio, $dto->studio, 'Can\'t access property "studio"');
        self::assertEquals($enabled, $dto->enabled, 'Can\'t access property "enabled"');
        self::assertEquals($name, $dto->getName(), 'Can\'t access property "name" with magic getter');
        self::assertEquals($studio, $dto->getStudio(), 'Can\'t access property "studio" with magic getter');
        self::assertEquals($enabled, $dto->isEnabled(), 'Can\'t access property "enabled" with magic getter');

        $dto->studio = 'override';
        self::assertEquals('override', $dto->studio, 'Can\'t write property "studio"');
        self::assertEquals('override magic', $dto->setStudio('override magic')->getStudio(), 'Can\'t write property "studio" with magic setter');
    }
}
