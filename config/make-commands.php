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
        'path-datafiles' => base_path().'/database/seeders/data',
        'classes' => [
            // Database\Seeders\JsonSeeders\UserSeeder::class,
        ],
    ],
];
