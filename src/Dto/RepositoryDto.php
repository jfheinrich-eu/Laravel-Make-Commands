<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Dto;

use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Collection<string,mixed> $attributes
 */
final class RepositoryDto extends DataTransferObject
{
    /**
     * @param null|int $id
     * @param null|Collection<string,mixed> $attributes
     */
    public function __construct(
        protected ?int $id = null,
        protected ?Collection $attributes = null
    ) {
    }
}
