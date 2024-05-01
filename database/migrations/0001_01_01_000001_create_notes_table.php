<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->year('year');
            $table->string('process');
            $table->string('modality');
            $table->string('modality_process');
            $table->string('service');
            $table->decimal('amount', 10, 2);
            $table->decimal('monthly_payment', 10, 2);
            $table->longText('comments')->nullable();
            $table->boolean('active')->default(true);
            $table->date('start');
            $table->date('end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
