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
        Schema::table('shifts', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | SUPERVISOR
            |--------------------------------------------------------------------------
            */
            $table->foreignId('supervisor_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | SHIFT OPERATIONS
            |--------------------------------------------------------------------------
            */
            $table->text('instructions')->nullable();

            $table->text('notes')->nullable();

            /*
            |--------------------------------------------------------------------------
            | LOCATION COORDINATES
            |--------------------------------------------------------------------------
            */
            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();

            /*
            |--------------------------------------------------------------------------
            | DOCUMENT / ATTACHMENT
            |--------------------------------------------------------------------------
            */
            $table->string('attachment')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {

            $table->dropForeign(['supervisor_id']);

            $table->dropColumn([
                'supervisor_id',
                'instructions',
                'notes',
                'latitude',
                'longitude',
                'attachment'
            ]);

        });
    }
};
