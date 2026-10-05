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
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technical_task_id')->constrained('technical_tasks')->cascadeOnDelete();
            $table->string('repository_url')->nullable();
            $table->text('notes')->nullable();
            $table->string('file_path')->nullable();
            $table->dateTime('submitted_at');
            $table->unsignedInteger('score')->nullable(); // 0 - 100
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};
