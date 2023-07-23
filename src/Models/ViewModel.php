<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Models;

use Dflydev\DotAccessData\Exception\DataException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use JfheinrichEu\LaravelMakeCommands\Support\Helper;

class ViewModel extends Model
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
     * @param array<string,mixed> $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->initialize();
    }

    /**
     * Initialize the model
     *
     * @return void
     */
    protected function initialize(): void
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
     * @return Collection<int,ViewModel>|ViewModel
     * @throws \Throwable
     */
    public static function create(array $attributes): Collection|ViewModel
    {
        $class = get_called_class();
        /** @var ViewModel $model */
        $model = (new $class());

        try {
            DB::beginTransaction();

            $tableAttributes = Arr::only($attributes, $model->getMainTableAttributes());

            $mainTableModel = $model->getModelByTableName($model->getMainTable());

            /** @var ViewModel $baseModel */
            $baseModel = $mainTableModel::create($tableAttributes);

            $foreignKey = $baseModel->getForeignKey();

            foreach ($model->getAllTableAttributes() as $table => $cols) {
                if ($table !== $model->getMainTable()) {
                    $tableAttributes = Arr::only($attributes, $cols);

                    $tableModel = $model->getModelByTableName($table);

                    $tableAttributes[$foreignKey] = $baseModel->getKey();
                    $tableModel::create($tableAttributes);
                }
            }

            DB::commit();

            return $model->newQuery()->whereKey($baseModel->getKey())->firstOrFail();
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
                ViewModel::create($singleData);
            }
        } catch (\Throwable) {
            return false;
        }

        return true;
    }

    /**
     * Update the model in the database.
     *
     * @param  array<string,mixed>  $attributes
     * @param  array<string,mixed>  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (!$this->exists) {
            return false;
        }

        try {
            $mainTableModel = $this->getModelByTableName($this->getMainTable());

            $foreignKey = $mainTableModel->getForeignKey();

            $id = $this->getKey();

            foreach ($this->getAllTableAttributes() as $table => $cols) {
                $tableAttributes = Arr::only($attributes, $cols);

                if ([] !== $tableAttributes) {
                    $tableModel = $this->getModelByTableName($table);

                    if ($table !== $this->getMainTable()) {
                        $tableAttributes[$foreignKey] = $id;
                        $result = $tableModel::query()
                            ->where($foreignKey, '=', $id)
                            ->firstOrFail()
                            ->fill($tableAttributes)
                            ->save($options);
                    } else {
                        $result = $tableModel::query()
                            ->where($tableModel->getKeyName(), '=', $id)
                            ->firstOrFail()
                            ->fill($tableAttributes)
                            ->save($options);
                    }

                    if (!$result) {
                        throw new DataException();
                    }
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return false;
        }

        return true;

        //return $this->fill($attributes)->save($options);
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

            /** @var ViewModel $model */
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
     * @return Model
     * @throws ModelNotFoundException
     */
    public function getModelByTableName(string $table): Model
    {
        /** @var string[] */
        $namespaces = Config::get('make_commands.useview.namespaces', ['App\\\Models\\']);

        $className = Str::studly(str::singular($table));

        foreach ($namespaces as $namespace) {
            /** @var class-string $fqn */
            $fqn = $namespace . $className;
            if (Helper::classExists($fqn)) {
                /** @var Model $model */
                $model = new $fqn();

                return $model;
            }
        }

        throw new ModelNotFoundException("Model $className not found");
    }

    public function getMainTable(): string
    {
        return $this->mainTable;
    }

    /**
     * @return string[]
     */
    public function getBaseTables(): array
    {
        return $this->baseTables;
    }

    /**
     * @return string[]
     */
    public function getMainTableAttributes(): array
    {
        return $this->tableAttributes[$this->mainTable];
    }

    /**
     * @return array<string,string[]>
     */
    public function getAllTableAttributes(): array
    {
        return $this->tableAttributes;
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
