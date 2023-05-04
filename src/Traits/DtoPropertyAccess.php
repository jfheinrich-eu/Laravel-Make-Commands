<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

use JfheinrichEu\LaravelMakeCommands\Exceptions\PropertyDoesNotExistsException;

trait DtoPropertyAccess
{
    /**
     * @param string $property
     * @throws PropertyDoesNotExistsException
     * @return mixed
     */
    public function __get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new PropertyDoesNotExistsException("Property >{$property}< does not exists");
    }

    /**
     * @param string $property
     * @param mixed $value
     * @throws PropertyDoesNotExistsException
     * @return void
     */
    public function __set(string $property, mixed $value): void
    {
        if (property_exists($this, $property)) {
            $this->$property = $value;
            return;
        }

        throw new PropertyDoesNotExistsException("Property >{$property}< does not exists");
    }
}
