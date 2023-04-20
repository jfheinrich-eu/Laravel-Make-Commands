<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $signature = 'make-commands:repository
                            {name : The repository name}
                            {--m|model= : The model on which the repository is based}';

    /**
     * @var string
     */
    protected $description = 'Create a new repository';

    /**
     * @var string
     */
    protected $type = 'Repository';

    /**
     * @var string
     */
    protected $dir = __DIR__;

    protected function getStub(): string
    {
        if (File::exists(base_path('stubs/make-commands/repository.stubs'))) {
            return base_path('stubs/make-commands/repository.stubs');
        } else {
            return $this->dir . '/../../../stubs/repository.stub';
        }
    }

    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Repositories";
    }
}
