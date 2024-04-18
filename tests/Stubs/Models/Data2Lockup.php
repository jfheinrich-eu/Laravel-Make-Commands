<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;

class Data2Lockup extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'd2l_id';

    protected $fillable = [
        'interests',
        'data_1_lockups_id',
    ];
}