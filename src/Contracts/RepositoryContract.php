<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use JfheinrichEu\LaravelMakeCommands\Dto\RepositoryDto;

interface RepositoryContract
{
    public function all(): EloquentCollection;
    public function create(RepositoryDto $dto): \Eloquent|Model;
    public function update(RepositoryDto $dto): bool;
    public function delete(int $id): bool;
    public function find(int $id): \Eloquent|Model;
}
