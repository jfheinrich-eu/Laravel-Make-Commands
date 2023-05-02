<?php

declare(strict_types=1);

return [
    /*
     * List of all commands to be registered.
     */
    'commands' => [
        JfheinrichEu\LaravelMakeCommands\Console\Commands\DtoMakeCommand::class,
        JfheinrichEu\LaravelMakeCommands\Console\Commands\InterfaceMakeCommand::class,
        JfheinrichEu\LaravelMakeCommands\Console\Commands\RepositoryMakeCommand::class,
        JfheinrichEu\LaravelMakeCommands\Console\Commands\ServiceMakeCommand::class,
    ],
    'seeders'  => [
        'path-seeder' => database_path('seeders'),
        'path-datafiles' => database_path('seeders/data'),
        'models' => [
            // App\Models\User::class,
        ],
    ],
];
