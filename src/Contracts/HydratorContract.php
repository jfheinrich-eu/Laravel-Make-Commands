<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

interface HydratorContract
{
    /**
     * @param class-string<DtoContract> $class
     * @param array<string,mixed> $properties
     * @return DtoContract
     */
    public function fill(string $class, array $properties): DtoContract;
}
