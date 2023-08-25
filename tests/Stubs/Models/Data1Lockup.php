<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string $name
 * @property string email
 * @property Carbon $created_at
 * @property Carbon updated_at
 */
class Data1Lockup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
    ];
}