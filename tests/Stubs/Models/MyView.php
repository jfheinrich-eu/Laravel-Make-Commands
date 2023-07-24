<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use Eloquent;
use JfheinrichEu\LaravelMakeCommands\Models\ViewModel;

/**
 * JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models\MyView
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $dl2_id
 * @property string $interests
 * @property int $data1_lockup_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MyView newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MyView newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MyView query()
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereDl2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereInterests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereData1LockupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MyView whereUpdatedAt($value)
 * @mixin Eloquent
 *
 * @use UseView<MyView>
 */
class MyView extends ViewModel
{
    /**
     * @var string
     */
    protected $table = 'my_view';

    /**
     * @var string[]
     */
    protected array $baseTables = [
        'data1_lockups',
        'data2_lockups',
    ];
}
