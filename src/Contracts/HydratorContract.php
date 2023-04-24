<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;

interface HydratorContract
{
    /**
     * fill
     *
     * @param  class-string<DataTransferObject> $class
     * @param  array<string,mixed> $properties
     * @return DataTransferObject
     */
    public function fill(string $class, array $properties): DataTransferObject;
}
