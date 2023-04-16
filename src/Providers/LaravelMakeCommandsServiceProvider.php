<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use JfheinrichEu\LaravelMakeCommands\Hydrator\Hydrate;
use JfheinrichEu\LaravelMakeCommands\Contracts\HydratorContract;

final class LaravelMakeCommandsServiceProvider extends ServiceProvider
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
        /** @var string[] $commands */
        $commands = Config::get('laravel-make-commands.commands', []);

        if ($commands !== []) {
            if ($this->app->runningInConsole()) {
                $this->commands(
                    commands: $commands,
                );
            }
        }
    }
}
