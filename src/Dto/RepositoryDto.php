<?php declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Dto;

use Illuminate\Database\Eloquent\Collection;
use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;

/**
 * @property int $id
 * @property Collection $attributes
 */
final class RepositoryDto extends DataTransferObject
{
    public function __construct(
        protected ?string $id = null,
        protected ?Collection $attributes = null
    ) {
    }
}
