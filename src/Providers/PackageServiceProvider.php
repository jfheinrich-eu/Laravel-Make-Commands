<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Providers;

use Illuminate\Support\ServiceProvider;
use JfheinrichEu\LaravelMakeCommands\Hydrator\Hydrate;
use JfheinrichEu\LaravelMakeCommands\Contracts\HydratorContract;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\DtoMakeCommand;

final class PackageServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string,class-string>
     */
    public array $bindings = [
        HydratorContract::class => Hydrate::class,
    ];

    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    DtoMakeCommand::class,
                ],
            );
        }
    }
}
