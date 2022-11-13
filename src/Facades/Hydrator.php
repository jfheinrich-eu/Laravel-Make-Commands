<?php

declare(strict_types=1);

namespace Jfheinrich\DataObjects\Facades;

use Illuminate\Support\Facades\Facade;
use Jfheinrich\DataObjects\Contracts\DataObjectContract;
use Jfheinrich\DataObjects\Hydrator\Hydrate;

/**
 * @method static DataObjectContract fill(string $class, array $properties)
 *
 * @see \Jfheinrich\DataObjects\Hydrator\Hydrate;
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
