<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Support\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use JfheinrichEu\LaravelMakeCommands\Exceptions\InvalidPathSeederConfigurationException;
use JfheinrichEu\LaravelMakeCommands\Traits\JsonSeeder;
use ParseError;

class DatabaseJsonSeeder extends Seeder
{
    use JsonSeeder;
    use WithoutModelEvents;

    public function run(): void
    {
        /** @var array<int,Seeder> $seeders */
        $seeders = [];

        /** @var array<int,class-string> $models */
        $models = Config::get('make-commands.seeders.models', []);

        if (is_array($models)) {
            foreach ($models as $model) {
                try {
                    /** @var  Seeder $seederObject */
                    $seederObject = $this->createSeederObject($model);

                    $seederObject->setCommand($this->command)->setContainer($this->container);

                    $seeders[] = $seederObject;
                } catch (ParseError | InvalidPathSeederConfigurationException $e) {
                    $this->command->getOutput()->error("Could not create a seeder class for model {$model}");
                }
            }

            $this->callSeederObject($seeders);
        }
    }


}
