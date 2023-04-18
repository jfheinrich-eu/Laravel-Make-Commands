<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

final class DtoMakeCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $signature = "make-commands:dto {name : The DTO Name}";

    /**
     * @var string
     */
    protected $description = "Create a new DTO";

    /**
     * @var string
     */
    protected $type = 'Data Transfer Object';

    /**
     * @var string
     */
    protected $dir = __DIR__;

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $readonly = Str::contains(
            haystack: PHP_VERSION,
            needles: '8.2',
        );

        $file = $readonly ? 'dto-82.stub' : 'dto.stub';

        return $this->dir . "/../../../stubs/{$file}";
    }

    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\DTO";
    }
}
