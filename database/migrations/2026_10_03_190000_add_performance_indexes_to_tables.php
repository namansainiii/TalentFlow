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
        Schema::table('jobs', function (Blueprint $table) {
            $table->index('status');
            $table->index('department');
            $table->index('recruiter_id');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index('status');
            $table->index('candidate_id');
        });

        Schema::table('resumes', function (Blueprint $table) {
            $table->index('candidate_id');
            $table->index('status');
        });

        Schema::table('application_status_histories', function (Blueprint $table) {
            $table->index('application_id');
        });

        Schema::table('interviews', function (Blueprint $table) {
            $table->index('scheduled_at');
            $table->index('status');
            $table->index('interviewer_id');
            $table->index('application_id');
        });

        Schema::table('technical_tasks', function (Blueprint $table) {
            $table->index('deadline');
            $table->index('status');
            $table->index('assigned_by_user_id');
            $table->index('application_id');
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->index('technical_task_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['department']);
            $table->dropIndex(['recruiter_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['candidate_id']);
        });

        Schema::table('resumes', function (Blueprint $table) {
            $table->dropIndex(['candidate_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('application_status_histories', function (Blueprint $table) {
            $table->dropIndex(['application_id']);
        });

        Schema::table('interviews', function (Blueprint $table) {
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['status']);
            $table->dropIndex(['interviewer_id']);
            $table->dropIndex(['application_id']);
        });

        Schema::table('technical_tasks', function (Blueprint $table) {
            $table->dropIndex(['deadline']);
            $table->dropIndex(['status']);
            $table->dropIndex(['assigned_by_user_id']);
            $table->dropIndex(['application_id']);
        });

        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropIndex(['technical_task_id']);
        });
    }
};
