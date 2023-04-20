<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use JfheinrichEu\LaravelMakeCommands\Dto\RepositoryDto;

interface RepositoryContract
{
    /**
     * @return EloquentCollection<int,Model>
     */
    public function all(): EloquentCollection;

    /**
     * @param RepositoryDto $dto
     * @return Model
     */
    public function create(RepositoryDto $dto): Model;

    /**
     * @param RepositoryDto $dto
     */
    public function update(RepositoryDto $dto): bool;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param int $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public function find(int $id): Model;
}
