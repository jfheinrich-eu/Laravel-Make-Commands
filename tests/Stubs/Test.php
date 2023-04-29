<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs;

use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;

/**
 * @property-read string $name
 * @property string $studio
 * @method string getName()
 * @method string getStudio()
 * @method void setStudio(string $studio)
 */
final class Test extends DataTransferObject
{
    public function __construct(
        protected readonly string $name,
        protected string $studio
    ) {
    }
}
