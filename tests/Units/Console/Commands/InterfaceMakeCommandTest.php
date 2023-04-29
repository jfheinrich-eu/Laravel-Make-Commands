<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\InterfaceMakeCommand;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use ReflectionClass;

final class InterfaceMakeCommandTest extends PackageTestCase
{
    public function test_run_the_command_successfully(): void
    {
        $this->artisan(InterfaceMakeCommand::class, ['name' => 'Test'])
            ->assertSuccessful();
    }

    public function test_create_the_interface_when_called(): void
    {
        $interface = 'TestInterface';

        $this->artisan(
            InterfaceMakeCommand::class,
            ['name' => $interface],
        )->assertSuccessful();

        self::assertTrue(
            File::exists(
                app_path("Contracts/{$interface}.php"),
            ),
        );
    }

    public function test_check_get_stub_method(): void
    {
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
    }
}
