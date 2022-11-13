<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\DtoMakeCommand;

use function PHPUnit\Framework\assertTrue;

it('can run the command successfully', function () {
    $this
        ->artisan(DtoMakeCommand::class, ['name' => 'Test'])
        ->assertSuccessful();
});

it('create the data transfer object when called', function (string $class) {
    $this->artisan(
        DtoMakeCommand::class,
        ['name' => $class],
    )->assertSuccessful();

    assertTrue(
        File::exists(
            path: app_path("DTO/$class.php"),
        ),
    );
})->with('classes');
