<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Contracts;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryContract
{
	public function all(): EloquentCollection;
	public function create(Collection $data): \Eloquent|Model;
	public function update(Collection $data): bool;
	public function delete(int $id): bool;
	public function find(int $id);
}
