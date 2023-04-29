<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

use BadMethodCallException;
use Illuminate\Support\Str;

trait DtoGetterAndSetter
{
    /**
     * @param string $methodName
     * @param array<int,mixed>|null $params
     * @return mixed
     * @throws BadMethodCallException
     */
    public function __call(string $methodName, ?array $params): mixed
    {
        $propertiesMethods = $this->getProperties();

        $methodPrefix = substr($methodName, 0, 3);
        $key = substr($methodName, 3);

        if ($methodPrefix === 'set' && $params !== null && count($params) == 1) {
            $value = $params[0];

            if (isset($propertiesMethods[$key])) {
                $property = $propertiesMethods[$key];
                $this->$property = $value;
                return $this;
            } else {
                throw new BadMethodCallException("Property {$propertiesMethods[$key]} does not exists");
            }
        } elseif ($methodPrefix === 'get') {
            if (isset($propertiesMethods[$key])) {
                $property = $propertiesMethods[$key];
                return $this->$property;
            } else {
                throw new BadMethodCallException("Property {$propertiesMethods[$key]} does not exists");
            }
        }

        throw new BadMethodCallException("Method {$methodName} does not exists");
    }

    /**
     * @return array<string,string>
     */
    protected function getProperties(): array
    {
        /** @var array<string,string> $propertyMethod */
        $propertyMethod = [];
        /** @var array<string,mixed> $properties */
        $properties = get_object_vars($this);

        foreach ($properties as $property => $value) {
            $method = ucfirst(Str::camel($property));
            $propertyMethod[$method] = $property;
        }

        return $propertyMethod;
    }
}
