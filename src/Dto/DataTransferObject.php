<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Dto;

use JfheinrichEu\LaravelMakeCommands\Contracts\DtoContract;
use JfheinrichEu\LaravelMakeCommands\Traits\DtoTransformation;
use JfheinrichEu\LaravelMakeCommands\Library\Magic\MagicMethods;

class DataTransferObject extends MagicMethods implements DtoContract
{
    use DtoTransformation;
}
