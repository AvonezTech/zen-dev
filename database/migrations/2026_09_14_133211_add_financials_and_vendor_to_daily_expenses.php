<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_expenses', 'paid_amount')) {
                $table->decimal('paid_amount', 14, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('daily_expenses', 'payment_status')) {
                $table->string('payment_status')->default('paid')->index()->after('payment_method');
            }
            if (!Schema::hasColumn('daily_expenses', 'due_amount')) {
                $table->decimal('due_amount', 14, 2)->default(0)->after('paid_amount');
            }
            if (!Schema::hasColumn('daily_expenses', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('project_id')->constrained('vendors')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_expenses', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn(['paid_amount', 'payment_status', 'due_amount', 'vendor_id']);
        });
    }
};
