<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Facades;

use Illuminate\Support\Facades\Facade;
use JfheinrichEu\LaravelMakeCommands\Hydrator\Hydrate;
use JfheinrichEu\LaravelMakeCommands\Contracts\DtoContract;

/**
 * @method static DtoContract fill(string $class, array $properties)
 *
 * @see \JfheinrichEu\LaravelMakeCommands\Hydrator\Hydrate;
 */
final class Hydrator extends Facade
{
    /**
     * @return class-string
     */
    protected static function getFacadeAccessor(): string
    {
        return Hydrate::class;
    }
}
