<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

use JsonSerializable;
use JustSteveKing\DataObjects\Contracts\DataObjectContract;

interface DtoContract extends JsonSerializable, DataObjectContract
{
    /**
     * @return mixed
     */
    public function JsonSerialize(): mixed;
}
