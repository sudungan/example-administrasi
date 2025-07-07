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
        Schema::table('addition_role_user', function (Blueprint $table) {
            // reference_id mereferensikan ke table terkait dan id table terkait
            $table->unsignedBigInteger('reference_id')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addition_role_user', function (Blueprint $table) {
            //
        });
    }
};
