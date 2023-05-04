<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Library\Magic;

use JfheinrichEu\LaravelMakeCommands\Traits\DtoGetterAndSetter;
use JfheinrichEu\LaravelMakeCommands\Traits\DtoPropertyAccess;

abstract class MagicMethods
{
    use DtoPropertyAccess;
    use DtoGetterAndSetter;
}
