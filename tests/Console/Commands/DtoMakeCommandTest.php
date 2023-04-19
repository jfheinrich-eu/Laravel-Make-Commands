<?php

declare(strict_types=1);

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
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
            app_path("DTO/$class.php"),
        ),
    );
})->with('classes');

it('check getStub() method', function () {
    $php82 = Str::contains(
        haystack: PHP_VERSION,
        needles: '8.2',
    );

    $test = new DtoMakeCommand(new Filesystem());

    $reflection = new ReflectionClass(
        objectOrClass: DtoMakeCommand::class,
    );

    $property = $reflection->getProperty('dir');
    $dir = $property->getValue($test);

    $method = $reflection->getMethod('getStub');
    $method->setAccessible(true);

    $expected = $dir . '/../../../stubs/dto.stub';
    if($php82) {
        $expected = $dir . '/../../../stubs/dto-82.stub';
    }

    $stub = $method->invoke($test);

    $this->assertEquals($expected, $stub);
});
