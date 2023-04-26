<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use InvalidArgumentException;

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
        if (File::exists(base_path('stubs/make-commands/repository.stub'))) {
            // @codeCoverageIgnoreStart
            return base_path('stubs/make-commands/repository.stub');
        // @codeCoverageIgnoreEnd
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

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name): string
    {
        $replace = [
            '{{ namespacedModel }}' => '',
            '{{namespacedModel}}'   => '',
            '{{ model }}'           => '',
            '{{model}}'             => '',
            '{{ modelVariable }}'   => '',
            '{{modelVariable}}'     => '',
        ];

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param array<string,string> $replace
     * @return array<string,string>
     */
    protected function buildModelReplacements(array $replace): array
    {
        /** @var string $model */
        $model = $this->option('model');

        $modelClass = $this->parseModel(($model));

        if (!class_exists($modelClass) && $this->components->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
            $this->call('make:model', ['name' => $modelClass]);
        }

        return array_merge($replace, [
            '{{ namespacedModel }}' => $modelClass,
            '{{namespacedModel}}'   => $modelClass,
            '{{ model }}' => class_basename($modelClass),
            '{{model}}'             => class_basename($modelClass),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
            '{{modelVariable}}' => lcfirst(class_basename($modelClass)),
        ]);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

}
