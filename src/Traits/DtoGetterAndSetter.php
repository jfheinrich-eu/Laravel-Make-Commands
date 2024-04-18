<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

use BadMethodCallException;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * @codeCoverageIgnore
 */
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

        $length = 3;
        if (Str::of($methodName)->startsWith('is')) {
            $length = 2;
        }

        $methodPrefix = substr($methodName, 0, $length);
        $key = substr($methodName, $length);

        if (!isset($propertiesMethods[$key])) {
            throw new BadMethodCallException("Property {$key} does not exists");
        }

        if ($methodPrefix === 'set' && $params !== null && count($params) == 1) {
            $value = $params[0];

            $property = $propertiesMethods[$key]['property'];
            $this->$property = $value;
            return $this;
        } elseif ($methodPrefix === $propertiesMethods[$key]['getter']) {
            $property = $propertiesMethods[$key]['property'];
            return $this->$property;
        }

        throw new BadMethodCallException("Method {$methodName} does not exists");
    }

    /**
     * @return array<string,array{property:string, getter:string}>
     */
    protected function getProperties(): array
    {
        /** @var array<string,array{property:string, getter:string}> $propertyMethod */
        $propertyMethod = [];

        $reflect = new ReflectionClass($this);
        $props = $reflect->getProperties();

        foreach ($props as $property) {
            $paramType = $property->getType();

            if ($paramType instanceof \ReflectionNamedType) {
                $typeName = $paramType->getName();
            } else { //if ($paramType instanceof \ReflectionUnionType)
                $typeName = (string) $paramType;
            }

            $propertyName = $property->getName();
            $method = ucfirst(Str::camel($propertyName));

            if ($typeName === 'bool') {
                $propertyMethod[$method] = [
                    'property' => $propertyName,
                    'getter' => 'is',
                ];
            } else {
                $propertyMethod[$method] = [
                    'property' => $propertyName,
                    'getter' => 'get',
                ];
            }
        }

        return $propertyMethod;
    }
}
