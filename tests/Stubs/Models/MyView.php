<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use JfheinrichEu\LaravelMakeCommands\Models\ViewModel;

class MyView extends ViewModel
{
    /**
     * @var string
     */
    protected $table = 'my_view';

    /**
     * @var string[]
     */
    protected $baseTables = [
        'data_1_lockups',
        'data_2_lockups',
    ];

    protected $attributes = [
        'id',
        'd2l_id',
        'name',
        'email',
        'interests',
        'data_1_lockups_id',
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}