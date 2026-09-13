<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('daily_expenses') && !Schema::hasColumn('daily_expenses', 'due_amount')) {
            Schema::table('daily_expenses', function (Blueprint $table) {
                $table->decimal('due_amount', 14, 2)->default(0)->after('paid_amount');
            });
        }
    }

    public function down(): void
    {
        Schema::table('daily_expenses', function (Blueprint $table) {
            $table->dropColumn(['due_amount']);
        });
    }
};
