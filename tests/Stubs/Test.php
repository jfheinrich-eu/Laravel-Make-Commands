<?php

declare(strict_types=1);

namespace Jfheinrich\DataObjects\Tests\Stubs;

use Jfheinrich\DataObjects\Contracts\DataObjectContract;

final class Test implements DataObjectContract
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function JsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        /*return [
            'name' => $this->name,
        ];*/
        return get_object_vars($this);
    }
}
