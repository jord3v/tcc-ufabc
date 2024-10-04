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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('report_id')->constrained();
            $table->string('invoice');
            $table->string('process')->nullable();
            $table->string('status')->nullable();
            $table->date('reference');
            $table->json('occurrences')->nullable();
            $table->decimal('price', 10, 2);
            $table->date('due_date');
            $table->date('signature_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
