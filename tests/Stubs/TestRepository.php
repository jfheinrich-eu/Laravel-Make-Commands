<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use JfheinrichEu\LaravelMakeCommands\Dto\RepositoryDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TestRepository
{
    public function __construct(protected User $user)
    {
    }

    /**
     * @return Collection<int, User>
     */
    public function all(): Collection
    {
        return $this->user::all();
    }

    /**
     * @param RepositoryDto $dto
     * @return User
     */
    public function create(RepositoryDto $dto): User
    {
        return $this->user::create($dto->attributes->toArray());
    }

    /**
     * @param RepositoryDto $dto
     * @return bool
     */
    public function update(RepositoryDto $dto): bool
    {
        return $this->user->find($dto->id)
            ?->update($dto->attributes->toArray()) ?? false;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->user->find($id)?->delete() ?? false;
    }

    /**
     * @param int $id
     * @return User
     * @throws ModelNotFoundException
     */
    public function find(int $id): Model| User
    {
        $model = $this->user->find($id);
        if (null == $model) {
            throw new ModelNotFoundException("Resource not found");
        }

        return $model;
    }
}
