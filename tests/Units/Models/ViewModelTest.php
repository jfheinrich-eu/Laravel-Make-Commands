<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Units\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        /** @var \Illuminate\Testing\PendingCommand $cmd */
        $cmd = $this->artisan(
            'migrate',
            [
                '--database' => 'testbench',
            ]
        );
        $cmd->run();
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

    public function test_create_update_delete_find(): void
    {
        /**
         * @var MyView $model
         */
        $model = MyView::create([
            'name' => 'Willi Wucher',
            'email' => 'willi.wucher@wucher.de',
            'interests' => 'Geld',
        ]);

        self::assertInstanceOf(MyView::class, $model, 'create: Returned class is not a instance of MyView');
        self::assertEquals('Willi Wucher', $model->name, 'create: Returned name is different from expected');
        self::assertEquals('willi.wucher@wucher.de', $model->email, 'create: Returned email is different from expected');
        self::assertEquals('Geld', $model->interests, 'create: Returned interests is different from expected');

        $this->view_test_find_after_create();

        $this->view_test_update();

        $this->view_test_find_after_update();

        $this->view_test_delete();
    }

    public function view_test_update(): void
    {
        $result = MyView::whereId(1)->firstOrFail()->update([
            'name' => 'Willi M. Wucher',
            'interests' => 'Money',
        ]);

        self::assertTrue((bool) $result, 'Update failed');
    }

    public function view_test_find_after_create(): void
    {
        /** @var MyView $model */
        $model = MyView::whereId(1)->firstOrFail();

        self::assertEquals('Willi Wucher', $model->name, 'find(create): Returned name is different from expected');
        self::assertEquals('willi.wucher@wucher.de', $model->email, 'find(create): Returned email is different from expected');
        self::assertEquals('Geld', $model->interests, 'find(create): Returned interests is different from expected');
    }

    public function view_test_find_after_update(): void
    {
        /** @var MyView $model */
        $model = MyView::whereId(1)->firstOrFail();

        self::assertEquals('Willi M. Wucher', $model->name, 'find(insert): Returned name is different from expected');
        self::assertEquals('willi.wucher@wucher.de', $model->email, 'find(insert): Returned email is different from expected');
        self::assertEquals('Money', $model->interests, 'find(insert): Returned interests is different from expected');
    }

    public function view_test_delete(): void
    {
        $result = MyView::whereId(1)->firstOrFail()->delete();

        self::assertTrue((bool) $result, 'Delete failed');
    }
}
