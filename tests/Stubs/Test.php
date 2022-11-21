<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs;

use JfheinrichEu\LaravelMakeCommands\Contracts\DtoContract;
use JfheinrichEu\LaravelMakeCommands\Traits\useDTO;

final class Test implements DtoContract
{
    use useDTO;

    public function __construct(
        private readonly string $name,
    ) {
    }
}
