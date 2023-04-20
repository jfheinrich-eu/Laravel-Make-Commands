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
- [Data transfer object (DTO) (make-commands:dto)](#data-transfer-object-dto-make-commandsdto)
  - [Usage](#usage)
  - [Object Hydration](#object-hydration)
- [Credits](#credits)

## Installation

```bash
$ composer require jfheinrich-eu/laravel-make-commands
```

To publish the assets, run following command:

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

## Data transfer object (DTO) (make-commands:dto)

Based on the great work of [Steve McDougall](https://github.com/JustSteveKing) with his package [laraval-data-object-tools](https://github.com/JustSteveKing/laravel-data-object-tools).

I extend this package with implementation of „JsonSerializable“.

### Usage

To generate a new DTO all you need to do is run the following artisan command:

```bash
php artisan make-commands:dto MyDto
```

This will generate the following class: `app/DTO/MyDto.php`. By default this class
will be a `final` class that implements a `DtoContract`, which extends `JsonSerializable`, which enforces two methods

- `toArray` so that you can easily cast your DTOs to arrays
- `JsonSerialize` so that you can easily serialize your DTOs

If you are using PHP 8.2 however, you will by default get a `readonly` class generated, so that you do not have
to declare each property as readonly.

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

## Credits

- [Joerg Heinrich](https://gitlab.com/j.f.heinrich)
- [Steve McDougall](https://github.com/JustSteveKing)
