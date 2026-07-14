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
        Schema::table('payrolls', function (Blueprint $table) {

            // Additional Earnings
            $table->decimal('overtime', 12, 2)->default(0)->after('bonus');

            // Additional Deductions
            $table->decimal('pension', 12, 2)->default(0)->after('tax');
            $table->decimal('nhf', 12, 2)->default(0)->after('pension');
            $table->decimal('loan', 12, 2)->default(0)->after('nhf');
            $table->decimal('other_deduction', 12, 2)->default(0)->after('loan');

            // Audit Fields
            $table->foreignId('paid_by')
                ->nullable()
                ->after('approved_by')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by');

            $table->timestamp('paid_at')
                ->nullable()
                ->after('paid_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            //
        });
    }
};
