<?php

use App\Models\{Classroom, Subject, User,};
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
        Schema::create('subjects_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->integer('total_jp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects_teacher');
    }
};
