<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Data1Lockup extends Model
{
    protected $table = 'data_1_lockups';

    protected $fillable = [
        'name',
        'email',
    ];
}