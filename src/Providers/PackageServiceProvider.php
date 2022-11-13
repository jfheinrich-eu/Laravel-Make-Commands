<?php

declare(strict_types=1);

namespace Jfheinrich\DataObjects\Providers;

use Illuminate\Support\ServiceProvider;
use Jfheinrich\DataObjects\Console\Commands\DataTransferObjectMakeCommand;
use Jfheinrich\DataObjects\Contracts\HydratorContract;
use Jfheinrich\DataObjects\Hydrator\Hydrate;

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
                    DataTransferObjectMakeCommand::class,
                ],
            );
        }
    }
}
