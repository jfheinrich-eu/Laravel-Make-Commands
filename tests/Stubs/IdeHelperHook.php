<?php

declare(strict_types=1);

namespace App\Support\IdeHelper;

use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TestView implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {
        if (!$model instanceof \App\Support\IdeHelper\TestView) {
            return;
        }

        $replace = [
            'varchar' => 'string',
            'bigint' => 'int',
            'tinyint' => 'int',
            'datetime' => 'string',
            'timestamp' => 'mixed',
            'enum' => 'string',
        ];

        $table = $model->getTable();

        $properties = DB::select(
            query: 'select column_name, data_type, is_nullable from information_schema.COLUMNS c WHERE c.TABLE_NAME = ? ORDER BY c.ORDINAL_POSITION',
            bindings: [$table],
        );

        foreach ($properties as $property) {
            $name = $property->column_name;
            $datatype = $replace[$property->data_type] ?? $property->data_type;

            $command->setProperty(
                name: $name,
                type: $datatype,
                read: true,
                write: true,
                comment: '',
                nullable: $property->is_nullable === 'YES'
            );

            $method = 'where' . Str::studly(str::singular($name));
            $command->setMethod($method, '\Illuminate\Database\Eloquent\Builder|TestView', ['$value']);
        }
    }
}