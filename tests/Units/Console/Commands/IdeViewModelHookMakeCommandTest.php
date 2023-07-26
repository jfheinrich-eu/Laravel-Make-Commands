<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\IdeViewModelHookMakeCommand;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;

final class IdeViewModelHookMakeCommandTest extends PackageTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->beforeApplicationDestroyed(function () {
            File::cleanDirectory(app_path());
        });
    }

    public function test_can_run_the_command_successfully(): void
    {
        // @phpstan-ignore-next-line
        $this->artisan(IdeViewModelHookMakeCommand::class, ['name' => 'TestView'])->assertSuccessful();
    }

    public function test_create_ide_helper_hook_when_called(): void
    {
        $class = 'TestView';

        // @phpstan-ignore-next-line
        $this->artisan(
            IdeViewModelHookMakeCommand::class,
            ['name' => $class],
        )->assertSuccessful();

        self::assertTrue(
            File::exists(
                app_path("Support/IdeHelper/$class.php"),
            ),
        );

        self::assertFileEquals(__DIR__ . '/../../../Stubs/IdeHelperHook.php', app_path("Support/IdeHelper/$class.php"));
    }
}
