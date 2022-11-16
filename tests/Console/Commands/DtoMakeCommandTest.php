<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
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

it('check getStub() method', function () {
    $test = new DtoMakeCommand(new Filesystem());

    $reflection = new ReflectionClass(
        objectOrClass: DtoMakeCommand::class,
    );

    $property = $reflection->getProperty('dir');
    $dir = $property->getValue($test);

    $method = $reflection->getMethod('getStub');
    $method->setAccessible(true);

    $expected = $dir . '/../../../stubs/dto.stub';
    $stub = $method->invoke($test);

    $this->assertEquals($expected, $stub);
});
