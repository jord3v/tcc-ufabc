<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The tables to add the sector_id column to.
     *
     * @var array
     */
    protected $tables = ['notes'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('sector_id')->nullable()->constrained()->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['sector_id']);
                $table->dropColumn('sector_id');
            });
        }
    }
};
