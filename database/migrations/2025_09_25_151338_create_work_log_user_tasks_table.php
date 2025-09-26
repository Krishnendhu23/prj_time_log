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
        Schema::create('work_log_user_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_log_user_entry_id')->constrained('work_log_user_entries')->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->text('task_description');
            $table->string('log_hours', 5); // store in HH:MM format
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_log_user_tasks');
    }
};
