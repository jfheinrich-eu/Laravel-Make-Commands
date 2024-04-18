<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use JfheinrichEu\LaravelMakeCommands\Support\Helper;

/**
 * Trait to extend the Eloquent model to handle database views
 *
 * @template T of Model
 * @phpstan-use HasAttributes<T>
 */
trait UseView
{
    /** @var array<string,string[]> */
    protected array $tableAttributes = [];

    /**
     * @var string
     */
    protected string $mainTable = '';

    /**
     * @var string[]
     */
    protected array $baseTables = [];

    /**
     * Initialize the trait
     *
     * @return void
     */
    protected function initializeUseView(): void
    {
        $this->appends[] = 'is_view';

        if ([] !== $this->baseTables) {
            if ($this->mainTable === '' || !in_array($this->mainTable, $this->baseTables, true)) {
                $this->mainTable = $this->baseTables[0];
            }

            foreach ($this->baseTables as $table) {
                $this->tableAttributes[$table] = DB::getSchemaBuilder()
                    ->getColumnListing($table);
            }
        }

        if ([] === $this->attributes) {
            $this->attributes = DB::getSchemaBuilder()
                ->getColumnListing($this->table);
        }
    }

    /**
     * Create a new database entry (static version)
     *
     * @codeCoverageIgnore
     *
     * @param array<string,mixed> $attributes
     * @return self
     * @throws \Throwable
     */
    public static function create(array $attributes): self
    {
        return (new self())->realCreate($attributes);
    }

    /**
     * Create a new database entry
     *
     * @codeCoverageIgnore
     *
     * @param array<string,mixed> $attributes
     * @return self
     * @throws \Throwable
     */
    public function realCreate(array $attributes): self
    {
        try {
            DB::beginTransaction();

            $tableAttributes = Arr::only($attributes, $this->tableAttributes[$this->mainTable]);

            $mainTableModel = $this->getModelByTableName($this->mainTable);

            /** @var T $baseModel */
            $baseModel = $mainTableModel::create($tableAttributes);

            $foreignKey = $baseModel->getForeignKey();

            foreach ($this->tableAttributes as $table => $cols) {
                if ($table !== $this->mainTable) {
                    $tableAttributes = Arr::only($attributes, $cols);

                    $tableModel = $this->getModelByTableName($table);

                    $tableAttributes[$foreignKey] = $baseModel->getKey();
                    $tableModel::create($tableAttributes);
                }
            }

            DB::commit();

            return $this::findOrFail($baseModel->getKey());
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }

    }

    /**
     * Implementation of the insert command for database views.
     *
     * @codeCoverageIgnore
     *
     * @param array<string,mixed>|array<int,array<string,mixed>> $values
     * @return bool
     */
    public function insert(array $values): bool
    {
        try {
            if (!is_array($values[0])) {
                $values = [$values];
            }

            /** @var array<string,mixed> $singleData */
            foreach ($values as $singleData) {
                $this->realCreate($singleData);
            }
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    /**
     * Implementation of the delete command for database views.
     *
     * @codeCoverageIgnore
     *
     * @return bool|null
     * @throws \LogicException
     */
    public function delete(): bool|null
    {
        try {
            DB::beginTransaction();

            $mainTableModel = $this->getModelByTableName($this->mainTable);

            /** @var T $model */
            $model = $mainTableModel->findOrFail($this->getKey());
            if ($model->delete() !== true) {
                throw new \RuntimeException();
            }

            DB::commit();

            return true;
        } catch (\LogicException $e) {
            try {
                DB::rollBack();
            } catch (\Throwable) {
            }

            throw $e;
        } catch (ModelNotFoundException) {
            try {
                DB::rollBack();
            } catch (\Throwable) {
            }

            return null;
        } catch (\Throwable) {
            try {
                DB::rollBack();
            } catch (\Throwable) {
            }

            return false;
        }
    }

    /**
     * Implementation of the truncate command for database views.
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function truncateView(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->baseTables as $table) {
            if ($table !== $this->mainTable) {
                DB::table($table)
                    ->truncate();
            }
        }
        DB::table($this->mainTable)
            ->truncate();

        Schema::enableForeignKeyConstraints();

    }

    /**
     * Find a model class by table name and returns a model instance.
     *
     * @param string $table
     * @return T
     * @throws ModelNotFoundException
     */
    public function getModelByTableName(string $table): Model
    {
        /** @var string[] */
        $namespaces = Config::get('make_commands.useview.namespaces', ['App\\\Models\\']);

        $className = Str::studly(str::singular($table));

        foreach ($namespaces as $namespace) {
            $fqn = $namespace . $className;
            if (Helper::classExists($fqn)) {
                /** @var T $model */
                $model = new $fqn();

                return $model;
            }
        }

        throw new ModelNotFoundException("Model $className not found");
    }

    /**
     * Determine that this model based on a database view and not on a table.
     *
     * @return Attribute<bool,mixed>
     */
    protected function isView(): Attribute
    {
        return new Attribute(
            get: fn () => true
        );
    }
}
