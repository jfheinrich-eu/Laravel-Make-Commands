<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\RepositoryMakeCommand;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use ReflectionClass;

final class RepositoryMakeCommandTest extends PackageTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! File::exists(app_path('Models'))) {
            File::makeDirectory(app_path('Models'));
        }

        $this->beforeApplicationDestroyed(function () {
            File::cleanDirectory(app_path());
        });
    }

    public function test_ca_run_the_command_successfully(): void
    {
        // @phpstan-ignore-next-line
        $this->artisan(RepositoryMakeCommand::class, ['name' => 'Test', '--model' => 'User'])
            ->expectsQuestion("A App\\Models\\User model does not exist. Do you want to generate it?", false)
            ->assertSuccessful();
    }

    public function test_create_the_repository_when_called(): void
    {
        $class = 'TestRepository';
        $model = 'User';

        // @phpstan-ignore-next-line
        $this->artisan(
            RepositoryMakeCommand::class,
            [ 'name' => $class, '--model' => $model ],
        )->expectsQuestion("A App\\Models\\{$model} model does not exist. Do you want to generate it?", false)
            ->assertSuccessful();

        self::assertTrue(
            File::exists(
                app_path("Repositories/{$class}.php"),
            ),
            "Created repository Repository/${class} not found"
        );

        self::assertEquals(
            File::get(__DIR__ . '/../../../Stubs/TestRepository.php'),
            File::get(app_path("Repositories/TestRepository.php")),
            'Created repository not equal to template'
        );
    }


    /**
     * FIXME: Corrupt test, must be fixed
     * @return void
     */
    public function check_get_stub_method(): void
    {
        $test = new RepositoryMakeCommand(new Filesystem());

        $reflection = new ReflectionClass(
            objectOrClass: RepositoryMakeCommand::class,
        );

        $property = $reflection->getProperty('dir');
        $dir      = $property->getValue($test);

        $method = $reflection->getMethod('getStub');
        $method->setAccessible(true);

        $expected = $dir . '/../../../stubs/repository.stub';
        $stub     = $method->invoke($test);

        $this->assertEquals($expected, $stub);
    }
}
