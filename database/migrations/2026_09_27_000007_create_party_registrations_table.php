<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('party_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('party_master_id');
            $table->string('customer_name', 150);
            $table->string('mobile', 20);
            $table->string('email', 191)->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->unsignedInteger('guest_count');
            $table->string('venue', 500)->nullable();
            $table->text('requirements')->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamps();
            $table->index(['party_master_id', 'status']);
            $table->index('event_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('party_registrations');
    }
};
