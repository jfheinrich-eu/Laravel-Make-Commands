<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;

final class IdeViewModelHookMakeCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $signature = "make-commands:view-model-hook {name : The View Model}";

    /**
     * @var string
     */
    protected $description = "Create a IDE helper hook for view model";

    /**
     * @var string
     */
    protected $type = 'IDE helper hook';

    /**
     * @var string
     */
    protected $dir = __DIR__;

    /**
     * @return string
     */
    protected function getStub(): string
    {
        $file = 'viewhook.stub';

        if (File::exists(base_path("stubs/make-commands/ide-helper/{$file}"))) {
            // @codeCoverageIgnoreStart
            return base_path("stubs/make-commands/ide-helper/{$file}");
            // @codeCoverageIgnoreEnd
        } else {
            return $this->dir . "/../../../stubs/ide-helper/{$file}";
        }
    }

    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Support\\IdeHelper";
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $preStub = parent::buildClass($name);

        return $this->replaceModel($preStub, $name);
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceModel($stub, $name)
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        return str_replace(['{{ namespacedModel }}', '{{namespacedModel}}'], $name, str_replace(['DummyModel', '{{ model }}', '{{model}}'], $class, $stub));
    }
}
