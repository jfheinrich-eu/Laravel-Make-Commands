<?php declare(strict_types=1);

namespace JfheinrichEu\LaravelMakeCommands\Support\Database\Seeder;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class JfhDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $seeders = Config::get('make-commands.seeders', []);

        if (is_array($seeders)) {
            $this->call($seeders);
        }
    }
}