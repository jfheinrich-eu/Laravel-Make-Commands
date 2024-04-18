<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Support;

use ReflectionClass;
use ReflectionException;

final class Helper
{
    /**
     * @param class-string $class
     * @return bool
     */
    public static function classExists(string $class): bool
    {
        if (!class_exists($class, false)) {
            try {
                /** @throws ReflectionException */
                $search = new ReflectionClass($class);
                return true;
            } catch (ReflectionException) {
                return false;
            }
        }

        return true;
    }
}
