<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Models;

use Illuminate\Database\Eloquent\Model;
use JfheinrichEu\LaravelMakeCommands\Traits\Models\UseView;

class ViewModel extends Model
{
    /** @phpstan-use UseView<ViewModel> */
    use UseView;

    /**
     * @var string
     */
    protected $mainTable = '';

    /**
     * @var string[]
     */
    protected $baseTables = [];

    public function initialize(): void
    {
        $this->initializeUseView();
    }
}
