<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Support\Database\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use JfheinrichEu\LaravelMakeCommands\Exceptions\InvalidSeederDataDirectoryException;

class JsonSeeder extends Seeder
{
    use WithoutModelEvents;

    public function __construct(
        protected Model $model
    ) {
    }

    public function run() : void
    {
        $data = $this->getSeederData();

        if (is_array($data)) {
            Model::unguard();
            $this->truncate();

            foreach ($data as $chunk) {
                $this->model->newQuery()->insert($chunk);
            }

            Model::reguard();
        }
    }

    /**
     * Reads the seeder data from JSON file
     *
     * @return array<array<mixed>>|bool
     */
    protected function getSeederData() : array|bool
    {
        try {
            $basepath = $this->getDataDir();
        } catch (InvalidSeederDataDirectoryException $e) {
            return $this->error($e->getMessage());
        }

        try {
            $filename = $basepath . "/{$this->model->getTable()}.json";
            if (! File::exists($filename)) {
                return $this->error("File >{$filename}< does not exists");
            }
            if (! File::isReadable($filename)) {
                return $this->error("File >{$filename}< is not readable");
            }

            /** @var mixed[] $fulldata */
            $fulldata = json_decode(
                File::get($filename),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            return array_chunk(
                $fulldata,
                500
            );
        } catch (\JsonException $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * Get the configured data dir
     *
     * @return string
     * @throws InvalidSeederDataDirectoryException
     */
    protected function getDataDir() : string
    {
        /** @var string $basepath */
        $basepath = Config::get('make-commands.seeders.path-datafiles', '');

        if (! File::isDirectory($basepath)) {
            throw new InvalidSeederDataDirectoryException(
                "Configured seeder data directory >{$basepath}< not found"
            );
        }
        if (! File::isReadable($basepath)) {
            throw new InvalidSeederDataDirectoryException(
                "Configured seeder data directory >{$basepath}< not readable"
            );
        }

        return $basepath;
    }

    /**
     * Executes a truncate on $this->model table
     *
     * @return void
     */
    protected function truncate() : void
    {
        Schema::disableForeignKeyConstraints();

        DB::table($this->model->getTable())
            ->truncate();

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Display an artisan error message
     *
     * @param string $message
     * @return boolean
     */
    protected function error(string $message) : bool
    {
        /** @phpstan-ignore-next-line */
        $this->command->getOutput()->error($message);
        return false;
    }
}