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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            // Employee
            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            // Payroll Number
            $table->string('payroll_number')->unique();

            // Pay Period
            $table->date('period_start');
            $table->date('period_end');

            // Summary
            $table->unsignedInteger('total_shifts')->default(0);
            $table->decimal('total_hours', 8, 2)->default(0);

            // Earnings
            $table->decimal('gross_pay', 12, 2)->default(0);
            $table->decimal('allowance', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);

            // Deductions
            $table->decimal('deduction', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);

            // Final Amount
            $table->decimal('net_pay', 12, 2)->default(0);

            // Workflow
            $table->enum('status', [
                'Draft',
                'Generated',
                'Approved',
                'Paid',
                'Cancelled'
            ])->default('Draft');

            // Payment
            $table->date('payment_date')->nullable();
            $table->string('payment_reference')->nullable();

            // Audit
            $table->foreignId('generated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index(['employee_id', 'period_start', 'period_end']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
