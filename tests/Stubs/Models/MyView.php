<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use JfheinrichEu\LaravelMakeCommands\Models\ViewModel;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $dl2_id
 * @property string $interests
 * @property int $data_1_lockups_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @use UseView<MyView>
 */
class MyView extends ViewModel
{
    /**
     * @var string
     */
    protected $table = 'my_view';

    protected string $mainTable = 'data1_lockups';

    /**
     * @var string[]
     */
    protected array $baseTables = [
        'data1_lockups',
        'data2_lockups',
    ];

    protected $attributes = [
        'id',
        'd2l_id',
        'name',
        'email',
        'interests',
        'data1_lockup_id',
        'created_at',
        'updated_at',
    ];
}
