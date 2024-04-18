<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use JfheinrichEu\LaravelMakeCommands\Tests\Stubs\Models\Data1Lockup;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_2_lockups', function (Blueprint $table): void {
            $table->id('d2l_id');
            $table->string('interests');
            $table->foreignIdFor(Data1Lockup::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_2_lockups');
    }
};
