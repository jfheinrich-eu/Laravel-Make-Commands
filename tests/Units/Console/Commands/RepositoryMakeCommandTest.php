<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Console\Commands\RepositoryMakeCommand;
use ReflectionClass;

final class RepositoryMakeCommandTest extends PackageTestCase
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

    public function test_ca_run_the_command_successfully() : void
    {
        $this->artisan( RepositoryMakeCommand::class, [ 'name' => 'Test', '--model' => 'User' ] )
            ->expectsQuestion( "A App\\Models\\User model does not exist. Do you want to generate it?", false )
            ->assertSuccessful();
    }

    /**
     *
     * @dataProvider repository_provider
     * @param string $class
     * @param string $model
     * @return void
     */
    public function test_create_the_repository_when_called( string $class, string $model ) : void
    {
        $this->artisan(
            RepositoryMakeCommand::class,
            [ 'name' => $class, '--model' => $model ],
        )->expectsQuestion( "A App\\Models\\{$model} model does not exist. Do you want to generate it?", false )
            ->assertSuccessful();

        self::assertTrue(
            File::exists(
                app_path( "Repositories/{$class}.php" ),
            ),
            "Created repository Repository/${class} not found"
        );

        if ( $class === 'TestRepository' && $model === 'User' ) {
            self::assertEquals(
                File::get( __DIR__ . '/../../../Stubs/TestRepository.php' ),
                File::get( app_path( "Repositories/TestRepository.php" ) ),
                'Created repository not equal to template'
            );
        }
    }


    public function test_check_get_stub_methosd() : void
    {
        $test = new RepositoryMakeCommand( new Filesystem() );

        $reflection = new ReflectionClass(
            objectOrClass: RepositoryMakeCommand::class,
        );

        $property = $reflection->getProperty( 'dir' );
        $dir      = $property->getValue( $test );

        $method = $reflection->getMethod( 'getStub' );
        $method->setAccessible( true );

        $expected = $dir . '/../../../stubs/repository.stub';
        $stub     = $method->invoke( $test );

        $this->assertEquals( $expected, $stub );
    }

    // Provider

    static public function repository_provider() : array
    {
        return [ 
            [ 'TestRepository', 'User' ],
            [ 'MyRepository', 'Permission' ],
            [ 'SomethingRepository', 'User' ],
            [ 'PackageClassRepository', 'Package' ],
        ];
    }
}