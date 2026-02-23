<?php

use App\Models\Task;
use App\Models\User;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignIdFor(Task::class, 'parent_id')
                ->nullable()
                ->default(null);
            $table->string('title');
            $table->string('status');
            $table->string('priority');
            $table->unsignedInteger('estimated_days');
            $table->longText('description');
            $table->foreignIdFor(User::class, 'assigned_to_id');
            $table->date('start_date')
                ->nullable()
                ->default(null);
            $table->date('due_date')
                ->nullable()
                ->default(null);
            $table->timestamp('completed_at')
                ->nullable()
                ->default(null);
            $table->flowforgePositionColumn('position'); // Handles database-specific collations automatically
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
