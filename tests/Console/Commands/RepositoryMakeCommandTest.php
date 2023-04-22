<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\RepositoryMakeCommand;

use function PHPUnit\Framework\assertTrue;

it('can run the command successfully', function () {
    $this->artisan(RepositoryMakeCommand::class, ['name' => 'Test'], ['model' => 'User'])
        ->assertSuccessful();
});

it('create the repository when called', function (string $class, string $model) {
    $this->artisan(
        RepositoryMakeCommand::class,
        ['name' => $class],
        ['model' => $model],
    )->assertSuccessful();

    assertTrue(
        File::exists(
            app_path("Repositories/{$class}.php"),
        ),
    );
})->with('repositories');

it('check getStub() method', function () {
    $test = new RepositoryMakeCommand(new Filesystem());

    $reflection = new ReflectionClass(
        objectOrClass: RepositoryMakeCommand::class,
    );

    $property = $reflection->getProperty('dir');
    $dir = $property->getValue($test);

    $method = $reflection->getMethod('getStub');
    $method->setAccessible(true);

    $expected = $dir . '/../../../stubs/repository.stub';
    $stub = $method->invoke($test);

    $this->assertEquals($expected, $stub);
});
