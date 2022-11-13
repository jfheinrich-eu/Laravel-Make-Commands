<?php

declare(strict_types=1);

namespace Jfheinrich\DataObjects\Contracts;

use JsonSerializable;

interface DataObjectContract extends JsonSerializable
{
    /**
     * @return array<string,mixed>
     */
    public function toArray(): array;

    /**
     * @return mixed
     */
    public function JsonSerialize(): mixed;
}
