<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Facades;

use BadMethodCallException;
use JfheinrichEu\LaravelMakeCommands\Exceptions\PropertyDoesNotExistsException;
use JfheinrichEu\LaravelMakeCommands\Facades\Hydrator;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Test;

final class HydratorUnknownPropertyTest extends PackageTestCase
{
    public function test_hydrate_test_class_check_unknown_property_access(): void
    {
        $name = 'Jimi Hendirx';
        $studio = 'Electric Lady Studios';

        /** @var Test $dto */
        $dto = Hydrator::fill(
            Test::class,
            ['name' => $name, 'studio' => $studio],
        );

        try {
            $t = $dto->unknown; // @phpstan-ignore-line
            $t = $dto->getUnknown(); // @phpstan-ignore-line
            self::assertEquals(
                "Exception",
                "No Exception",
                'Read Access to unknown Property does not thrown a exception.'
            );
        } catch (PropertyDoesNotExistsException $e) {
            self::assertEquals(
                "Property >unknown< does not exists",
                $e->getMessage(),
                'Read access to unknown Property does not thrown expected exception and message.'
            );
        } catch (BadMethodCallException $e) {
            self::assertEquals(
                'Property >unknown< does not exists',
                $e->getMessage(),
                'Read access to unknown Property with magic getter does not thrown expected exception and message.'
            );
        }

        try {
            $dto->unknown = 42; // @phpstan-ignore-line
            $dto->setUnknown(42); // @phpstan-ignore-line
            self::assertEquals(
                "Exception",
                "No Exception",
                'Write Access to unknown Property does not thrown a exception.'
            );
        } catch (PropertyDoesNotExistsException $e) {
            self::assertEquals(
                "Property >unknown< does not exists",
                $e->getMessage(),
                'Write access to unknown Property does not thrown expected exception and message.'
            );
        } catch (BadMethodCallException $e) {
            self::assertEquals(
                'Property >unknown< does not exists',
                $e->getMessage(),
                'Write access to unknown Property with magic getter does not thrown expected exception and message.'
            );
        }

    }
}
