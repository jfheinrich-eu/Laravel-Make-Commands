<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

use Illuminate\Support\Collection;
use JsonSerializable;

interface DtoContract extends JsonSerializable
{
    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     *
     * @return Collection<int|string,mixed>
     */
    public function toCollection(): Collection;

    /**
     * @return mixed
     */
    public function JsonSerialize(): mixed;
}
