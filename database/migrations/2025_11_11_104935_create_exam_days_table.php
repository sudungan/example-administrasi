<?php

use App\Models\{Major, VocationalExam};
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
        Schema::create('exam_days', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_index');
            $table->foreignIdFor(Major::class);
            $table->foreignIdFor(VocationalExam::class);
            $table->date('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_days');
    }
};
