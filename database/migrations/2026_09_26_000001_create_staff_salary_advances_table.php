<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_salary_advances', function (Blueprint $table) {
            $table->id();
            // The legacy `pos` table may not support foreign-key constraints.
            // These IDs are validated and scoped by the application instead.
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);
            $table->date('advance_date');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['staff_id', 'advance_date']);
            $table->index(['user_id', 'advance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_salary_advances');
    }
};
