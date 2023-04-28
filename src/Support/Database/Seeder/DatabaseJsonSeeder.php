<?php

declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Support\Database\Seeder;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseJsonSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        /** @var array<int,class-string> $seeders */
        $seeders = Config::get('make-commands.seeders.classes', []);

        if (is_array($seeders)) {
            $this->call($seeders);
        }
    }
}
