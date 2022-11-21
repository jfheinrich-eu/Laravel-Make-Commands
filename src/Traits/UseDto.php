<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

use Illuminate\Support\Collection;

trait useDTO
{
    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @inheritDoc
     */
    public function toCollection(): Collection
    {
        return collect($this->toArray());
    }

    /**
     * @inheritDoc
     */
    public function JsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
