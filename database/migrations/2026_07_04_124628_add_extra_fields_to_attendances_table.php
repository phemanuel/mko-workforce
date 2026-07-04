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
        Schema::create('attendances', function (Blueprint $table) {

            $table->id();

            $table->foreignId('shift_assignment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('shift_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('employee_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();

            $table->decimal('check_in_lat',10,7)->nullable();
            $table->decimal('check_in_lng',10,7)->nullable();

            $table->decimal('check_out_lat',10,7)->nullable();
            $table->decimal('check_out_lng',10,7)->nullable();

            $table->integer('late_minutes')->default(0);
            $table->integer('early_leave_minutes')->default(0);

            $table->enum('status',[
                'Pending',
                'Checked In',
                'Checked Out',
                'Late',
                'Absent',
                'Early Leave'
            ])->default('Pending');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
        });
    }
};
