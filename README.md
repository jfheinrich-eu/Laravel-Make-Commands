# Laravel Make Commands <!-- omit in toc -->

<!-- BADGES_START -->
![Owner](https://gitlab.com/jfheinrich-eu/laravel-make-commands/-/jobs/artifacts/main/raw/public/badges/owner.svg?job=badges)
![Package](https://gitlab.com/jfheinrich-eu/laravel-make-commands/-/jobs/artifacts/main/raw/public/badges/name.svg?job=badges)
![Release](https://gitlab.com/jfheinrich-eu/laravel-make-commands/-/jobs/artifacts/main/raw/public/badges/release.svg?job=badges)
![Last Activity](https://gitlab.com/jfheinrich-eu/laravel-make-commands/-/jobs/artifacts/main/raw/public/badges/last_activity_at.svg?job=badges)
<!-- BADGES_END -->

This package is aimed to be a suite of artisan commands and tools to help make the work easier.

- [Installation](#installation)
- [Make interface (make-commands:interface)](#make-interface-make-commandsinterface)
  - [Example](#example)
- [Make repository (make-commands:repository)](#make-repository-make-commandsrepository)
  - [Usage:](#usage)
  - [Example](#example-1)
- [Make a service (make-commands:service)](#make-a-service-make-commandsservice)
  - [Example](#example-2)
- [Data transfer object (DTO) (make-commands:dto)](#data-transfer-object-dto-make-commandsdto)
  - [Usage](#usage-1)
  - [Example](#example-3)
  - [Work with the hydration functionality](#work-with-the-hydration-functionality)
  - [Object Hydration](#object-hydration)
- [JSON database seeder](#json-database-seeder)
- [Credits](#credits)

## Installation

```bash
$ composer require jfheinrich-eu/laravel-make-commands
```

To publish the assets and config file, run following command:

```bash
$ php artisan make-commands:install
```

This install the config file to [Project root]/app/config/make-commands.php and the stubs to [Project root]/stubs/make-commands.

To install only the config file, use this command:

```bash
$ php artisan vendor:publish --tag make-commands-config
```

To install only the stubs, use this command:

```bash
$ php artisan vendor:publish --tag make-commands-assets
```


## Make interface (make-commands:interface)

Creates a new interface in `app\Contracts`

```bash
php artisan make-commands:interface Interface
```

### Example

```bash
$ php artisan make-commands:interface TestInterface
$ cat app/Contracts/TestInterface.php
<?php declare(strict_types=1);

namespace App\Contracts;

interface TestInterface
{

}
```

## Make repository (make-commands:repository)

Creates a new repository.

Optionally you can give the model on which the repository should based on.
In this case, the command creates a repository with
- a protected property for the model, which can be created by dependency injection, over the constructor.
- it create a CRUD skeleton
  - all() : returns all records as a Eloquent collection

  - create(): creates a new record and returns then new record as Eloquent Model. The data for the record will be given by the generic `RepositoryDto` class as attribute collection.

  - update(): updates a existing record and returns boolean. The data to update will be given by the generic `RepositoryDto` class as attribute collection.

  - delete(): deletes a existing record and returns boolean. The deletion runs over the primary key.

  - find(): returns a Eloquent model, found by primary key. Is there no record with given primary key, a ModelNotFoundException will be thrown.

The generic class `RepositoryDto`:
```php
<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Dto;

use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property Collection<int,array<string,mixed>> $attributes
 */
final class RepositoryDto extends DataTransferObject
{
    /**
     * @param null|int $id
     * @param null|Collection<int,array<string,mixed>> $attributes
     */
    public function __construct(
        protected ?int $id = null,
        protected ?Collection $attributes = null
    ) {
    }
}
```

The attributes property of RepositoryDto gets the required columns from the model as illuminate\Support\Collection.

This collection can be constructed like this:

```php
$dto->setAttributes(collect(['id' => 42, 'email' => 'dummy@localhost.tld']);
```


### Usage:

```bash
$ php artisan make-commands:repository <Repository name> --model=<Modelname>
```

### Example

```bash
$ php artisan make-commands:repository UserRepository --model=User
```

`app/Repositories/UserRepository.php`
```php
<?php declare(strict_types=1);

namespace App\Repositories

use Illuminate\Database\Eloquent\Collection;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use JfheinrichEu\LaravelMakeCommands\Dto\RepositoryDto;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TestRepository
{
    public function __construct(protected User $user) {}

    public function all(): Collection
    {
        return $this->user::all();
    }

    /**
     * @inheritdoc
     */
    public function create(RepositoryDto $dto): Model|User
    {
        return $this->user::create($dto->attributes->toArray());
    }

    /**
     * @inheritdoc
     */
    public function update(RepositoryDto $dto): bool
    {
        return $this->user->whereId($dto->id)
            ->update($dto->attributes->toArray());
    }

    /**
     * @inheritdoc
     */
    public function delete(int $id): bool
    {
        return $this->user->whereId($id)->delete($id);
    }

    /**
     * @inheritdoc
     */
    public function find(int $id): Model| User
    {
        $model = $this->user->find($id)
        if (null == $model)
        {
            throw new ModelNotFoundException("Resource not found");
        }

        return $model;
    }
}
```

## Make a service (make-commands:service)

Creates a new service class, which can optionally implement a existing interface and can based on a existing repository.

```bash
php artisan make-commands:service name [--interface=Interface] [--repository=Repository]
```

### Example

Use existing interface `App\Contracts\UserPostInterface`
```php
<?php declare(strict_types=1);

namespace App\Contracts;

interface UserPostInterface
{
	public function get( string|array|null $title, ?int $userId = 0): array|string;
}
```

```bash
$ php artisan make-commands:service UserPostService --interface=UserPostInterface --repository=UserRepository
```

`app/Services/UserPostService.php`

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\UserPostInterface;
use App\Repositories\UserRepository;

class UserPostService implements UserPostInterface
{
    public function __construct(protected UserRepository $userRepository)
    {
    }


    public function get(array|string|null $title,?int $userId = 0): array|string
    {
        // Implementation
    }


}
```

## Data transfer object (DTO) (make-commands:dto)

Based on the great work of [Steve McDougall](https://github.com/JustSteveKing) with his package [laraval-data-object-tools](https://github.com/JustSteveKing/laravel-data-object-tools).

I extend this package with implementation of „JsonSerializable“.

### Usage

To generate a new DTO all you need to do is run the following artisan command:

```bash
php artisan make-commands:dto MyDto
```

This will generate the following class: `app/Dto/MyDto.php`. By default this class
will be a `final` class that implements a `DtoContract`, which extends `JsonSerializable`, which enforces two methods

- `toArray` so that you can easily cast your DTOs to arrays
- `JsonSerialize` so that you can easily serialize your DTOs

If you are using PHP 8.2 however, you will by default get a `readonly` class generated, so that you do not have
to declare each property as readonly.

### Example

```bash
$ php artisan make-commands:dto MyDto
```

`app/Dto/MyDto.php`

```php
<?php

declare(strict_types=1);

namespace App\Dto;

use JfheinrichEu\LaravelMakeCommands\Dto\DataTransferObject;

final class MyDto extends DataTransferObject
{
    public function __construct(
        //
    ) {}
}
```

### Work with the hydration functionality

To work with the hydration functionality you can either use Laravels DI container, or the ready made facade.

Using the container:

```php
class StoreController
{
    public function __construct(
        private readonly HydratorContract $hydrator,
    ) {}

    public function __invoke(StoreRequest $request)
    {
        $model = Model::query()->create(
            attributes: $this->hydrator->fill(
                class: ModelObject::class,
                parameters: $request->validated(),
            )->toArray(),
        );
    }
}
```

To work with the facade, you can do something very similar:

```php
class StoreController
{
    public function __invoke(StoreRequest $request)
    {
        $model = Model::query()->create(
            attributes: Hydrator::fill(
                class: ModelObject::class,
                parameters: $request->validated(),
            )->toArray(),
        );
    }
}
```

### Object Hydration

Under the hood this package uses an [EventSauce](https://eventsauce.io) package, created by [Frank de Jonge](https://twitter.com/frankdejonge). It is possibly the
best package I have found to hydrate objects nicely in PHP. You can find the [documentation here](https://github.com/EventSaucePHP/ObjectHydrator)
if you would like to see what else you are able to do with the package to suit your needs.

## JSON database seeder

The database seeder in this package

- DatabaseJsonSeeder
- JsonSeeder

are designed to seed a table from a json data file.

All what you have to do is to
integrate `DatabaseJsonSeeder` in `Database\Seeder\DatabaseSeeder`

```php
<?php

namespace Database\Seeders;

use use Illuminate\Database\Seeder;
use JfheinrichEu\LaravelMakeCommands\Support\Database\Seeder\DatabaseJsonSeeder;

class DatabaseSeeder extends DatabaseJsonSeeder
{
    protected array $nonJsonSeeders = [
        // Non JSON Seeder classes
    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // run the JSON seeders
        parent::run();

        // optional, run the non JSON seeder
        $this->call($nonJsonSeeders);

    }
}
```

After that, you can run the JSON seeders with
```bash
php artisan db:seed
```

## Credits

- [Joerg Heinrich](https://gitlab.com/j.f.heinrich)
- [Steve McDougall](https://github.com/JustSteveKing)
