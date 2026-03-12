<?php

use App\Models\Project;
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
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('project_id', 'taskable_id');
            $table->unsignedBigInteger('taskable_id')
                ->change();

            $table->string('taskable_type')
                ->default(Project::class);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('taskable_id', 'project_id');
            $table->bigInteger('taskable_id')
                ->change();
        });
    }
};
