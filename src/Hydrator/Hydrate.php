<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Hydrator;

use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use JfheinrichEu\LaravelMakeCommands\Contracts\DtoContract;
use JfheinrichEu\LaravelMakeCommands\Contracts\HydratorContract;

class Hydrate implements HydratorContract
{
    /**
     * @param ObjectMapperUsingReflection $mapper
     */
    public function __construct(
        private readonly ObjectMapperUsingReflection $mapper = new ObjectMapperUsingReflection(),
    ) {
    }

    /**
     * @inheritDoc
     */
    public function fill(string $class, array $properties): DtoContract
    {
        return $this->mapper->hydrateObject(
            className: $class,
            payload: $properties,
        );
    }
}
