<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\InterfaceMakeCommand;

use function PHPUnit\Framework\assertTrue;

it('can run the command successfully', function () {
    $this->artisan(InterfaceMakeCommand::class, ['name' => 'Test'])
        ->assertSuccessful();
});

it('create the data transfer object when called', function (string $class) {
    $this->artisan(
        InterfaceMakeCommand::class,
        ['name' => $class],
    )->assertSuccessful();

    assertTrue(
        File::exists(
            app_path("Contracts/{$class}.php"),
        ),
    );
})->with('interfaces');

it('check getStub() method', function () {
    $test = new InterfaceMakeCommand(new Filesystem());

    $reflection = new ReflectionClass(
        objectOrClass: InterfaceMakeCommand::class,
    );

    $property = $reflection->getProperty('dir');
    $dir = $property->getValue($test);

    $method = $reflection->getMethod('getStub');
    $method->setAccessible(true);

    $expected = $dir . '/../../../stubs/interface.stub';
    $stub = $method->invoke($test);

    $this->assertEquals($expected, $stub);
});
