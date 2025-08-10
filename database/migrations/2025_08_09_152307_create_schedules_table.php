<?php

use App\Models\{Classroom, Subject, TimeSlot};
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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TimeSlot::class);
            $table->foreignIdFor(Subject::class);
            $table->foreignIdFor(Classroom::class);
            $table->string('type');
            $table->string('name')->nullable();
            $table->string('day');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
