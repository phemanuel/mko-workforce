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
        Schema::table('attendances', function (Blueprint $table) {

            $table->foreignId('resolved_by')
                ->nullable()
                ->after('remarks')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('resolved_at')
                ->nullable()
                ->after('resolved_by');

            $table->enum('resolution_method', [
                'shift_end',
                'current_time',
                'custom_time'
            ])->nullable()->after('resolved_at');

            $table->text('resolution_reason')
                ->nullable()
                ->after('resolution_method');
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
