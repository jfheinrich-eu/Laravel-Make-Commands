<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs;

use JfheinrichEu\LaravelMakeCommands\Contracts\DtoContract;

final class Test implements DtoContract
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function JsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
