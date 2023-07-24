<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $dl2_id
 * @property string $interests
 */
class Data2Lockup extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $primaryKey = 'd2l_id';

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'interests',
        'data1_lockup_id',
    ];
}
