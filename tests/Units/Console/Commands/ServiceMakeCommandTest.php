<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\ServiceMakeCommand;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;

final class ServiceMakeCommandTest extends PackageTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!File::exists(app_path('Contracts'))) {
            File::makeDirectory(app_path('Contracts'));
        }
        if (!File::exists(app_path('Repositories'))) {
            File::makeDirectory(app_path('Repositories'));
        }

        File::put(
            app_path('Contracts/TestInterface.php'),
            "<?php declare(strict_types=1);\n\nnamespace App\Contracts;\n\ninterface TestInterface\n{\n	public function get( string|array|null \$path, ?int \$id = 0): int|string;\n}\n"
        );

        $this->beforeApplicationDestroyed(function () {
            File::cleanDirectory(app_path());
        });
    }

    public function test_ca_run_the_command_successfully_without_interface_and_repository(): void
    {
        // @phpstan-ignore-next-line
        $this->artisan(ServiceMakeCommand::class, ['name' => 'TestService'])
            ->assertSuccessful();

        self::assertTrue(
            File::exists(app_path('Services/TestService.php')),
            'Created service class file not found'
        );
    }

    /**
     * FIXME: corrupt test
     * @return void
     */
    public function ca_run_the_command_successfully_with_interface(): void
    {
        // @phpstan-ignore-next-line
        $this->artisan(ServiceMakeCommand::class, ['name' => 'TestService', '--interface' => 'TestInterface'])
            ->expectsQuestion("A App\\Repositories\\TestRepository repository does not exist. Do you want to generate it?", true)
            ->assertSuccessful();

        self::assertTrue(
            File::exists(app_path('Services/TestService.php')),
            'Created service class file not found'
        );
    }

    public function test_ca_run_the_command_successfully_with_repository(): void
    {
        // @phpstan-ignore-next-line
        $this->artisan(ServiceMakeCommand::class, ['name' => 'TestService', '--repository' => 'TestRepository'])
            ->expectsQuestion("A App\\Repositories\\TestRepository repository does not exist. Do you want to generate it?", true)
            ->assertSuccessful();

        self::assertTrue(
            File::exists(app_path('Services/TestService.php')),
            'Created service class file not found'
        );

        self::assertTrue(
            File::exists(app_path('Repositories/TestRepository.php')),
            'Created service class file not found'
        );
    }

}
