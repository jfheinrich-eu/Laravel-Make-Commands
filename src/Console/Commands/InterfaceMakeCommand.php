<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;

final class InterfaceMakeCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $signature = 'make-commands:interface {name : The Interface Name}';

    /**
     * @var string
     */
    protected $description = 'Create a new interface';

    /**
     * @var string
     */
    protected $type = 'Interface';

    /**
     * @var string
     */
    protected $dir = __DIR__;

    protected function getStub(): string
    {
        return $this->dir . '/../../../stubs/interface.stub';
    }

    /**
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Contracts";
    }
}
