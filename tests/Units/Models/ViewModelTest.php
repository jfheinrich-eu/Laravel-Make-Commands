<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
use Mockery;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use JfheinrichEu\LaravelMakeCommands\Tests\PackageTestCase;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models\MyView;

final class ViewModelTest extends PackageTestCase
{

    public function setUp(): void
    {
        parent::setUp();

        if (!File::exists(app_path('Models'))) {
            File::makeDirectory(app_path('Models'));
        }

        File::copyDirectory(__DIR__ . '/../../Stubs/Models', app_path('Models'));
        File::copyDirectory(__DIR__ . '/../../Stubs/Migrations', database_path('migrations'));

        $this->beforeApplicationDestroyed(function () {
            File::cleanDirectory(database_path('migrations'));
            File::cleanDirectory(app_path());
        });
    }

    public function test_check_is_view(): void
    {
        $model = new MyView();

        self::assertTrue($model->getAttribute('is_view'), 'Attribute is_view return false');
    }

    public function test_get_model_by_table_name(): void
    {
        $model = new MyView();

        $result = $model->getModelByTableName('data_1_lockups');

        self::assertInstanceOf(Model::class, $result, 'Returned class is not a child class of Eloquent model');
    }

    public function test_get_model_by_table_name_exception(): void
    {
        self::expectException(ModelNotFoundException::class);

        $model = new MyView();

        $result = $model->getModelByTableName('not_exists');
    }

    public function test_create_insert_update_delete_find(): void
    {

        /** @var MyView $model */
        $model = MyView::create([
            'name' => 'Willi Wucher',
            'email' => 'willi.wucher@wucher.de',
            'interests' => 'Geld',
        ]);

        self::assertInstanceOf(MyView::class, $model, 'Returned class is not a instance of MyView');
        self::assertEquals('Willi Wucher', $model->name, 'Returned name is different from expected');
        self::assertEquals('willi.wucher@wucher.de', $model->email, 'Returned email is different from expected');
        self::assertEquals('Geld', $model->interests, 'Returned interests is different from expected');
    }
}