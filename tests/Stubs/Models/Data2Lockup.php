<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $dl2_id
 * @property string $interests
 */
class Data2Lockup extends Model
{
    protected $table = 'data_2_lockups';
    /**
     * @var string
     */
    protected $primaryKey = 'd2l_id';

    protected $fillable = [
        'interests',
        'data_1_lockups_id',
    ];
}
