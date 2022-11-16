<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

use JsonSerializable;

interface DtoContract extends JsonSerializable
{
    /**
     * @return array<string,mixed>
     */
    public function toArray(): array;

    /**
     * @return mixed
     */
    public function JsonSerialize(): mixed;
}
