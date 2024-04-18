<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use JfheinrichEu\LaravelMakeCommands\Models\ViewModel;

/**
 * JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models\MyView
 *
 * @use UseView<InvalidView>
 */
class InvalidView extends ViewModel
{
    /**
     * @var string
     */
    protected $table = 'my_view';

    /**
     * @var string[]
     */
    protected array $baseTables = [
        'invalid_data1_lockups',
        'invalid_data2_lockups',
    ];
}
