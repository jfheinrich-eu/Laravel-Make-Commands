<?php

declare(strict_types=1);

return [
    /*
     * List of all commands to be registered.
     */
    'commands' => [
        JfheinrichEu\LaravelMakeCommands\Console\Commands\DtoMakeCommand::class,
        JfheinrichEu\LaravelMakeCommands\Console\Commands\InterfaceMakeCommand::class,
    ],
];
