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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payroll_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('attendance_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('shift_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('shift_title');

            $table->date('shift_date');

            $table->decimal('hours_worked', 8, 2);

            $table->decimal('hourly_rate', 10, 2);

            $table->decimal('amount', 12, 2);

            $table->timestamps();

            $table->index('payroll_id');
            $table->index('attendance_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};
