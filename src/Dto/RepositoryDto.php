<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Dto;

use Illuminate\Support\Collection;
use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;

/**
 * @property int $id
 * @property Collection<int,array<string,mixed>> $attributes
 */
final class RepositoryDto extends DataTransferObject
{
    /**
     * @param null|int $id
     * @param null|Collection<int,array<string,mixed>> $attributes
     */
    public function __construct(
        protected ?int $id = null,
        protected ?Collection $attributes = null
    ) {
    }
}
