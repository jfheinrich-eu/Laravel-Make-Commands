<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Console\Commands;

use Illuminate\Console\Command;
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

        $models = $this->argument('model');

        if (! File::isDirectory($seederDataPath) || ! File::isWritable($seederDataPath)) {
            $this->error('Seeder data directory, configured as >' . $seederDataPath . '<, does not exists or is not writable, check your configuration');
            return SymfonyCommand::FAILURE;
        }

        if (! is_array($models)) {
            $models = [$models];
        }

        foreach ($models as $model) {
            try {
                /** @var Model $modelObject */
                $modelObject = app()->make($model);
            } catch (BindingResolutionException $e) {
                $this->error($e->getMessage());
                continue;
            }

            /** @var Collection<int,Model> $allData */
            $allData = $modelObject->get();

            $json = $allData->map(function ($item) {
                return $item->setAppends([]);
            })->toJson(JSON_PRETTY_PRINT);

            $jsonfile = $seederDataPath . DIRECTORY_SEPARATOR . $modelObject->getTable() . '.json';

            if (! File::put($jsonfile, $json)) {
                $this->error('Can not write JSON file ' . $jsonfile);

                $retCode = SymfonyCommand::FAILURE;
            }
        }

        return $retCode;
    }
}
