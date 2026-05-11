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
        Schema::create('shifts', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->text('description')->nullable();

            $table->date('shift_date');

            $table->time('start_time');

            $table->time('end_time');

            $table->string('location')->nullable();

            $table->string('role_required');

            $table->integer('required_staff')->default(1);

            $table->decimal('hourly_rate', 10, 2)->nullable();

            $table->enum('status', [
                'Open',
                'Assigned',
                'Completed',
                'Cancelled'
            ])->default('Open');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
