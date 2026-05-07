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
        //
        Schema::table('employee_documents', function (Blueprint $table) {
            $table->string('status')->default('pending'); 
            // pending | approved | rejected

            $table->text('rejection_reason')->nullable();

            $table->date('verified_at')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
