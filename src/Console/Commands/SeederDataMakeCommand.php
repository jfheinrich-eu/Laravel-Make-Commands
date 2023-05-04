<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\View\Components\TwoColumnDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * @codeCoverageIgnore
 */
class SeederDataMakeCommand extends Command
{
    /**
     *
     * @var string
     */
    protected $signature = 'make-commands:seeder-data
                            {model* : Models for the data files to be created}';

    /**
     * @var string
     */
    protected $description = 'Creates one or more datafiles for given models.';


    /**
     * @return int
     */
    public function handle(): int
    {
        $retCode = SymfonyCommand::SUCCESS;

        /** @var string $seederDataPath */
        $seederDataPath = Config::get('make-commands.seeders.path-datafiles', '');

        /** @var array<int,string>|string $models */
        $models = $this->argument('model');

        if (! File::isDirectory($seederDataPath) || ! File::isWritable($seederDataPath)) {
            $this->error('Seeder data directory, configured as >' . $seederDataPath . '<, does not exists or is not writable, check your configuration');
            return SymfonyCommand::FAILURE;
        }

        if (! is_array($models)) {
            $models = [$models];
        }

        $silent = ! $this->getOutput()->isQuiet();

        foreach ($models as $model) {

            if ($silent === false) {
                // @phpstan-ignore-next-line
                with(new TwoColumnDetail($this->getOutput()))->render(
                    $model,
                    '<fg=yellow;options=bold>RUNNING</>'
                );
            }

            $startTime = microtime(true);

            $retCode = $this->createJsonFile($model, $seederDataPath);

            if ($silent === false) {
                $runTime = number_format((microtime(true) - $startTime) * 1000, 2);

                // @phpstan-ignore-next-line
                with(new TwoColumnDetail($this->getOutput()))->render(
                    $model,
                    "<fg=gray>$runTime ms</> <fg=green;options=bold>DONE</>"
                );

                $this->getOutput()->writeln('');
            }

        }

        if ($retCode !== SymfonyCommand::SUCCESS) {
            $this->error('Some JSON data files could not be written.');
        }

        return $retCode;
    }

    protected function createJsonFile(string $model, string $seederDataPath): int
    {
        $retCode = SymfonyCommand::SUCCESS;

        try {
            /** @var Model $modelObject */
            $modelObject = app()->make($model);
        } catch (BindingResolutionException $e) {
            $this->error($e->getMessage());
            return SymfonyCommand::FAILURE;
        }

        /** @var Collection<int,Model> $allData */
        $allData = $modelObject->get();

        $json = $allData->map(function ($item) {
            /** @var  Model $item */
            return $item
                ->setAppends([])
                ->setHidden([])
                ->getAttributes();
        })->toJson(JSON_PRETTY_PRINT);

        $jsonfile = $seederDataPath . DIRECTORY_SEPARATOR . $modelObject->getTable() . '.json';

        if (! File::put($jsonfile, $json)) {
            $this->error('Can not write JSON file ' . $jsonfile);

            $retCode = SymfonyCommand::FAILURE;
        }

        return $retCode;
    }
}
