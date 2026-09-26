<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_tasks', function (Blueprint $table) {
            $table->id();
            // Kept as indexed IDs because the legacy `pos` table may not support FK constraints.
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('task_image')->nullable();
            $table->timestamps();

            $table->index('staff_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_tasks');
    }
};
