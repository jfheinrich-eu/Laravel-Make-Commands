<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

use Illuminate\Support\Collection;

trait UseDto
{
    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        return null;
    }

    public function __set(string $property, mixed $value): void
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

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
