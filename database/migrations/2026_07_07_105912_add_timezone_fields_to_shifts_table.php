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

            $table->string('timezone')
                ->default('Europe/London')
                ->after('end_time');

            $table->unsignedSmallInteger('check_in_open_minutes')
                ->default(30)
                ->after('timezone');

            $table->unsignedSmallInteger('late_after_minutes')
                ->default(15)
                ->after('check_in_open_minutes');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            //
        });
    }
};
