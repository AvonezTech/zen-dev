<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('daily_expenses') && !Schema::hasColumn('daily_expenses', 'paid_amount')) {
            Schema::table('daily_expenses', function (Blueprint $table) {
                $table->decimal('paid_amount', 14, 2)->default(0)->after('amount');
                $table->string('payment_status')->default('paid')->index()->after('payment_method');
            });
        }
    }

    public function down(): void
    {
        Schema::table('daily_expenses', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'payment_status']);
        });
    }
};
