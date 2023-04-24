<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\RepositoryMakeCommand;

final class RepositoryMakeCommand2Test extends PackageTestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->beforeApplicationDestroyed( function ()
        {
            File::cleanDirectory( app_path() );
            if ( ! File::exists( app_path( 'Models' ) ) ) {
                File::makeDirectory( app_path( 'Models' ) );
            }
        } );
    }

    public function test_run_the_command_with_make_modelsuccessfully() : void
    {
        $this->artisan( RepositoryMakeCommand::class, [ 'name' => 'Test', '--model' => 'User' ] )
            ->expectsQuestion( "A App\\Models\\User model does not exist. Do you want to generate it?", true )
            ->assertSuccessful();
    }

    public function test_run_the_command_with_illegal_model_name() : void
    {
        $this->expectException( InvalidArgumentException::class);

        $this->artisan( RepositoryMakeCommand::class, [ 'name' => 'Test', '--model' => 'U$$%ser' ] )
            ->assertFailed();
    }

}