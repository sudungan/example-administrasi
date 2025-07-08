<?php

use App\Models\{AdditionRole, User, };
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
        Schema::create('addition_role_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AdditionRole::class);
            $table->foreignIdFor(User::class);
            $table->unsignedBigInteger('reference_id');
            $table->string('reference_table');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addition_role_user');
    }
};
