<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs;

use JfheinrichEu\LaravelMakeCommands\Contracts\DtoContract;
use JfheinrichEu\LaravelMakeCommands\Traits\UseDTO;

final class Test implements DtoContract
{
    use UseDTO;

    public function __construct(
        private readonly string $name,
    ) {
    }
}
