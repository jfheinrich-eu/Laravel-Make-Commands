<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

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
        if (File::exists(base_path('stubs/make-commands/interface.stubs'))) {
            // @codeCoverageIgnoreStart
            return base_path('stubs/make-commands/interface.stubs');
            // @codeCoverageIgnoreEnd
        } else {
            return $this->dir . '/../../../stubs/interface.stub';
        }
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
