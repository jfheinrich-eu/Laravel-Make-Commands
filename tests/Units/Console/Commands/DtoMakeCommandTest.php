<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\DtoMakeCommand;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use ReflectionClass;

final class DtoMakeCommandTest extends PackageTestCase
{
    public function test_can_run_the_command_successfully(): void
    {
        // @phpstan-ignore-next-line
        $this->artisan(DtoMakeCommand::class, ['name' => 'Test'])->assertSuccessful();
    }

    public function test_create_the_data_transfer_object_when_called(): void
    {
        $class = 'Test';

        // @phpstan-ignore-next-line
        $this->artisan(
            DtoMakeCommand::class,
            ['name' => $class],
        )->assertSuccessful();

        self::assertTrue(
            File::exists(
                app_path("Dto/$class.php"),
            ),
        );
    }

    public function test_check_get_stub_method(): void
    {
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
        if ($php82) {
            $expected = $dir . '/../../../stubs/dto-82.stub';
        }

        $stub = $method->invoke($test);

        $this->assertEquals($expected, $stub);
    }
}
