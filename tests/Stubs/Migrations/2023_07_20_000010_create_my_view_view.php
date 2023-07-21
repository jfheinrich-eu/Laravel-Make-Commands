<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models\Data1Lockup;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {

    private string $viewName = 'my_view';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement($this->createView());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement($this->dropView());
    }

    private function createView(): string
    {
        return <<<SQL
            CREATE VIEW `{$this->viewName}` AS
                SELECT
                    d1l.id,
                    d1l.name,
                    d1l.email,
                    d2l.d2l_id,
                    d2l.interests,
                    d2l.data_1_lockups_id,
                    d1l.created_at,
                    d1l.updated_at
                  FROM data_1_lockups d1l JOIN data_2_lockups d2l ON d1l.id = d2l.data_1_lockups_id
SQL;
    }

    private fucntion dropView(): string
    {
        return "DROP VIEW IF EXISTS \`{$this->viewName}\`";
    }

};