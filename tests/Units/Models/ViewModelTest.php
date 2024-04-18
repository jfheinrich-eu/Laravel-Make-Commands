<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Models;

use Illuminate\Support\Facades\Config;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\MyView;

final class ViewModelTest extends PackageTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_check_is_view(): void
    {
        $model = new MyView();

        self::assertTrue($model->getAttribute('is_view'));
    }

    public function test_get_model_by_table_name(): void
    {
        Config::shouldReceive('offsetGet')
            ->once()
            ->with('make_commands.useview.namespaces', ['App\\\Models\\'])
            ->andReturn(['JfheinrichEu\\LaravelMakeCommands\\Tests\\Stubs\\']);
        Config::shouldReceive('offsetGet')
            ->once()
            ->with('database.default')
            ->andReturnNull();

        /** @var \Mockery\LegacyMockInterface|\Mockery\MockInterface|MyView $model */
        $model = \Mockery::mock(new MyView())->makePartial();

        $result = $model->getModelByTableName('my_view');

        self::assertInstanceOf(MyView::class, $result);
    }
}