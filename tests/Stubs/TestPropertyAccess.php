<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs;

use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;

/**
 * @property-read string $name
 * @property string $studio
 * @property bool $enabled
 * @method string getName()
 * @method string getStudio()
 * @method bool isEnabled()
 * @method void setStudio(string $studio)
 */
final class TestPropertyAccess extends DataTransferObject
{
    public function __construct(
        protected readonly string $name,
        protected string $studio,
        protected bool $enabled
    ) {
    }
}
