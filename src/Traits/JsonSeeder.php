<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Traits;

use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Console\View\Components\TwoColumnDetail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use JfheinrichEu\LaravelMakeCommands\Exceptions\InvalidPathSeederConfigurationException;
use ParseError;

trait JsonSeeder
{
    /**
     * Run the given seeder class.
     *
     * @param  Seeder[]|Seeder  $object
     * @param  bool  $silent
     * @param  mixed[]  $parameters
     * @return $this
     */
    public function callSeederObject($object, $silent = false, array $parameters = [])
    {
        /** @var Seeder[] $seeders */
        $seeders = Arr::wrap($object);

        foreach ($seeders as $seeder) {

            $name = get_class($seeder);

            // @phpstan-ignore-next-line
            if ($silent === false && isset($this->command)) {
                // @phpstan-ignore-next-line
                with(new TwoColumnDetail($this->command->getOutput()))->render(
                    $name,
                    '<fg=yellow;options=bold>RUNNING</>'
                );
            }

            $startTime = microtime(true);

            $seeder->__invoke($parameters);

            // @phpstan-ignore-next-line
            if ($silent === false && isset($this->command)) {
                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);

                // @phpstan-ignore-next-line
                with(new TwoColumnDetail($this->command->getOutput()))->render(
                    $name,
                    "<fg=gray>$runTime ms</> <fg=green;options=bold>DONE</>"
                );

                $this->command->getOutput()->writeln('');
            }

            static::$called[] = $name;
        }

        return $this;
    }

    /**
     * Summary of createSeederObject
     * @param class-string $model
     * @throws ParseError
     * @throws InvalidPathSeederConfigurationException
     * @return mixed
     */
    protected function createSeederObject(string $model): mixed
    {
        /** @var  string $seederPath */
        $seederPath = Config::get('make-commands.seeders.path-seeder', '');

        if ($seederPath === '') {
            throw new InvalidPathSeederConfigurationException('No configuration to the seeder class directory found');
        }

        File::ensureDirectoryExists($seederPath, 775);

        $modelShort = class_basename($model);
        $seederName = $modelShort . 'Seeder';
        $seederFqn = 'Database\Seeder\\' . $seederName;
        $filename = $seederPath . DIRECTORY_SEPARATOR . $seederName . '.php';

        $classTemplate = <<<EOF
namespace JfheinrichEu\\LaravelMakeCommands\\Support\\Database\\Seeder;

use $model;
use JfheinrichEu\\LaravelMakeCommands\\Support\\Database\\Seeder\\JsonSeeder;

class $seederName extends JsonSeeder {
    public function __construct($modelShort \$model)
    {
        parent::__construct(\$model);
    }
}
EOF;

        File::put(
            $filename,
            $classTemplate
        );

        return app()->make($seederFqn);
    }
}
