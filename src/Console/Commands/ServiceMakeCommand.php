<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Reflection;
use ReflectionClass;

class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * @var string
     */
    protected $signature = 'make-commands:service
                            {name : The service name}
                            {--r|repository= : The repository on which the service is based on}
                            {--i|interface= : Implements the Interface}';

    /**
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * @var string
     */
    protected $type = 'Service';

    /**
     * @var string
     */
    protected $dir = __DIR__;

    protected function getStub(): string
    {
        if (File::exists(base_path('stubs/make-commands/service.stub'))) {
            // @codeCoverageIgnoreStart
            return base_path('stubs/make-commands/service.stub');
        // @codeCoverageIgnoreEnd
        } else {
            return $this->dir . '/../../../stubs/service.stub';
        }
    }

    /**
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\Services";
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
            '{{useRepositoryClass}}'    => '',
            '{{ useRepositoryClass }}'  => '',
            '{{implementsInterface}}'   => '',
            '{{ implementsInterface }}' => '',
            '{{dependencyInjection}}'   => '',
            '{{ dependencyInjection }}' => '',
            '{{InterfaceStubs}}'        => '',
            '{{ InterfaceStubs }}'      => '',
        ];

        if ($this->option('repository')) {
            $replace = $this->buildRepositoryReplacements($replace);
        }

        if ($this->option('interface')) {
            $replace = $this->buildInterfaceReplacements($replace);
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Build the repository replacement values.
     *
     * @param array<string,string> $replace
     * @return array<string,string>
     */
    protected function buildRepositoryReplacements(array $replace): array
    {
        /** @var string $repository */
        $repository = $this->option('repository');

        $repositoryClass = $this->parseRepository(($repository));

        if (! class_exists($repositoryClass) && $this->components->confirm("A {$repositoryClass} repository does not exist. Do you want to generate it?", true)) {
            $this->call('make-commands:repository', [ 'name' => $repositoryClass ]);
        }

        $dependencyInjection = sprintf(
            "public function __construct(protected %s %s)\n    {\n    }\n",
            class_basename($repositoryClass),
            '$' . lcfirst(class_basename($repositoryClass))
        );

        return array_merge($replace, [
            '{{useRepositoryClass}}'    => "use {$repositoryClass};",
            '{{ useRepositoryClass }}'  => "use {$repositoryClass};",
            '{{dependencyInjection}}'   => $dependencyInjection,
            '{{ dependencyInjection }}' => $dependencyInjection,
        ]);
    }

    /**
     *
     * @param string $repository
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseRepository(string $repository): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $repository)) {
            throw new InvalidArgumentException('Repository name contains invalid characters.');
        }

        return $this->qualifyRepository($repository);
    }

    /**
     * Summary of qualifyRepository
     * @param string $repository
     * @return string
     */
    protected function qualifyRepository(string $repository): string
    {
        $repository = ltrim($repository, '\\/');

        $repository = str_replace('/', '\\', $repository);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($repository, $rootNamespace)) {
            return $repository;
        }

        return is_dir(app_path('Repositories'))
            ? $rootNamespace . 'Repositories\\' . $repository
            : $rootNamespace . $repository;
    }

    /**
     * Build the interface replacement values.
     *
     * @param array<string,string> $replace
     * @return array<string,string>
     */
    protected function buildInterfaceReplacements(array $replace): array
    {
        /** @var string $interface */
        $interface = $this->option('interface');

        $interfaceClass = $this->parseInterface(($interface));

        $rootNamespace = $this->rootNamespace();

        $filepath = str_replace(
            '\\',
            DIRECTORY_SEPARATOR,
            str_replace($rootNamespace, '', $interfaceClass)
        ) . '.php';

        if (! File::exists(app_path($filepath)) && $this->components->confirm("A {$interfaceClass} interface does not exist. Do you want to generate it?", true)) {
            $this->call('make-commands:interface', [ 'name' => class_basename($interfaceClass) ]);
        }

        $dependencyInjection = sprintf(
            "public function __construct(protected %s %s)\n    {\n    }\n",
            class_basename($interfaceClass),
            '$' . lcfirst(class_basename($interfaceClass))
        );

        $replace = $this->getMethodStubs($interfaceClass, $replace);

        return array_merge($replace, [
            '{{implementsInterface}}'   => "implements {$interfaceClass}",
            '{{ implementsInterface }}' => "implements {$interfaceClass}",
        ]);
    }

    /**
     * Summary of getMethodStubs
     * @param string $interfaceClass
     * @param array<string,string> $replace
     * @return array<string,string>
     */
    protected function getMethodStubs(string $interfaceClass, array $replace): array
    {
        /** @var class-string $classString */
        $classString         = $interfaceClass;
        $methodStubs         = '';
        $reflectionInterface = new ReflectionClass($classString);

        /** @var \ReflectionMethod[] $methodNames */
        $methodNames = $reflectionInterface->getMethods();

        foreach ($methodNames as $method) {
            $modifiers  = Reflection::getModifierNames($method->getModifiers());
            $parameters = $method->getParameters();
            $params     = [];

            foreach ($parameters as $param) {

                $paramType = $param->getType();

                if ($paramType instanceof \ReflectionNamedType) {
                    $typeName = $paramType->getName();
                    $nullable = $paramType->allowsNull() ? '?' : '';
                } else { //if ($paramType instanceof \ReflectionUnionType)
                    $nullable = '';
                    $typeName = (string) $paramType;
                }

                $paramName = $param->getName();
                $default   = '';

                if ($param->isDefaultValueAvailable()) {
                    $default = sprintf(
                        " = %s",
                        strval($param->getDefaultValue())
                    );
                }

                $line = sprintf(
                    "%s%s $%s%s",
                    $nullable,
                    $typeName,
                    $paramName,
                    $default
                );

                $params[ $param->getPosition()] = $line;
            }

            $reflectionReturnType = $method->getReturnType();
            if ($reflectionReturnType instanceof \ReflectionNamedType) {
                $returnType = $reflectionReturnType->allowsNull() ? '?' : '';
                $returnType .= $reflectionReturnType->getName();
            } else { //if ($reflectionReturnType instanceof \ReflectionUnionType)
                $returnType = (string) $reflectionReturnType;
            }

            ksort($params, SORT_NUMERIC);

            $methodStubs .= sprintf(
                "%s function(%s): %s\n    {\n        // Implementation\n    }\n\n",
                trim( str_replace( 'abstract', '', implode( ' ', $modifiers ) ) ),
                implode(',', $params),
                $returnType
            );
        }

        $replace[ '{{ InterfaceStubs }}' ] = $methodStubs;
        $replace[ '{{InterfaceStubs}}' ]   = $methodStubs;

        return $replace;
    }

    /**
     *
     * @param string $interface
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseInterface(string $interface): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $interface)) {
            throw new InvalidArgumentException('Interface name contains invalid characters.');
        }

        return $this->qualifyInterface($interface);
    }

    /**
     * Summary of qualifyInterface
     * @param string $interface
     * @return string
     */
    protected function qualifyInterface(string $interface): string
    {
        $interface = ltrim($interface, '\\/');

        $interface = str_replace('/', '\\', $interface);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($interface, $rootNamespace)) {
            return $interface;
        }

        return is_dir(app_path('Contracts'))
            ? $rootNamespace . 'Contracts\\' . $interface
            : $rootNamespace . $interface;
    }

}
