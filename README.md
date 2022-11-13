# Laravel Generator Commands <!-- omit in toc -->

<!-- BADGES_START -->
![Owner](../-/jobs/artifacts/main/raw/public/badges/owner.svg?job=badges)
![Package](../-/jobs/artifacts/main/raw/public/badges/name.svg?job=badges)
![Release](../-/jobs/artifacts/main/raw/public/badges/release.svg?job=badges)
![Last Activity](../-/jobs/artifacts/main/raw/public/badges/last_activity_at.svg?job=badges)
<!-- BADGES_END -->

This package is aimed to be a suite of artisan commands and tools to help make the work easier.

- [Installation](#installation)
- [Data transfer object (DTO) / make:dto](#data-transfer-object-dto--makedto)
  - [Usage](#usage)
  - [Object Hydration](#object-hydration)
- [Credits](#credits)

## Installation

```bash
composer config gitlab-token.gitlab.com <personal_access_token>

composer config repositories.jfheinrich-eu composer https://gitlab.com/api/v4/group/jfheinrich-eu/-/packages/composer/

composer require jfheinrich-eu/laravel-commands
```

## Data transfer object (DTO) / make:dto

Based on the great work of [Steve McDougall](https://github.com/JustSteveKing) with his package [laraval-data-object-tools](https://github.com/JustSteveKing/laravel-data-object-tools).

I extend this package with implementation of „JsonSerializable“.

### Usage

To generate a new DTO all you need to do is run the following artisan command:

```bash
php artisan make:dto MyDto
```

This will generate the following class: `app/DataObjects/MyDto.php`. By default this class
will be a `final` class that implements a `DataObjectContract`, which extends `JsonSerializable`, which enforces two methods

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
