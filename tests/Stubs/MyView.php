<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs;

use JfheinrichEu\LaravelMakeCommands\Models\ViewModel;

final class MyView extends ViewModel
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
}