<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

trait DtoMagic
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
}
