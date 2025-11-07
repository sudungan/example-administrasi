<?php

use App\Models\Major;
use App\Models\User;
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
        Schema::create('vocational_exam', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignIdFor(Major::class);
            $table->foreignIdFor(User::class); // untuk nama kepala jurusan
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocational_exam');
    }
};
