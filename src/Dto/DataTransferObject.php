<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Dto;

use JfheinrichEu\LaravelMakeCommands\Contracts\DtoContract;
use JfheinrichEu\LaravelMakeCommands\Traits\UseDto;

class DataTransferObject implements DtoContract
{
    use UseDto;
}
