<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Models;

use Illuminate\Database\Eloquent\Model;
use JfheinrichEu\LaravelMakeCommands\Traits\Models\UseView;

class ViewModel extends Model
{
    /** @phpstan-use UseView<ViewModel> */
    use UseView;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /**
     * @var string
     */
    protected $mainTable = '';

    /**
     * @var string[]
     */
    protected $baseTables = [];

}
