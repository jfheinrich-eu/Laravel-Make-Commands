<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;

final class ServiceMakeCommandTest extends PackageTestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->beforeApplicationDestroyed( function ()
        {
            File::cleanDirectory( app_path() );
            if ( ! File::exists( app_path( 'Contracts' ) ) ) {
                File::makeDirectory( app_path( 'Contracts' ) );
            }
            if ( ! File::exists( app_path( 'Repositories' ) ) ) {
                File::makeDirectory( app_path( 'Repositories' ) );
            }
        } );
    }

    public function test_ca_run_the_command_successfully_without_interface_and_repository() : void
    {
        $this->markTestSkipped( 'This test is incomplete and will be skipped' );

        $this->artisan( ServiceMakeCommand::class, [ 'name' => 'TestService' ] )
            ->assertSuccessful();
    }
}