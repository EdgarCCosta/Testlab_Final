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
        Schema::create('test_executions', function (Blueprint $table) {
            $table->id();

            // Relaciones
            $table->foreignId('test_case_id')->constrained('test_cases')->onDelete('cascade');
            $table->foreignId('version_id')->constrained('versions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Resultados de la ejecución
            $table->enum('result', ['passed', 'failed', 'blocked', 'pending'])->default('pending');
            $table->text('comment')->nullable();
            $table->json('test_data')->nullable();
            $table->enum('error_status', ['critical', 'high', 'medium', 'low', 'none'])->default('none');
            $table->text('correction_notes')->nullable();
            $table->text('observations')->nullable();
            $table->timestamp('executed_at')->nullable();

            $table->timestamps();

            // Índices para optimización
            $table->index(['test_case_id', 'version_id']);
            $table->index('result');
            $table->index('executed_at');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_executions');
    }
}; 