<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (!Schema::hasColumn('pos', 'date_of_joining')) $table->date('date_of_joining')->nullable();
            if (!Schema::hasColumn('pos', 'date_of_birth')) $table->date('date_of_birth')->nullable();
            if (!Schema::hasColumn('pos', 'gender')) $table->string('gender', 20)->nullable();
            // `designation` already exists on the legacy `pos` table.
            if (!Schema::hasColumn('pos', 'salary')) $table->decimal('salary', 12, 2)->nullable();
            if (!Schema::hasColumn('pos', 'address')) $table->text('address')->nullable();
            if (!Schema::hasColumn('pos', 'emergency_contact_name')) $table->string('emergency_contact_name')->nullable();
            if (!Schema::hasColumn('pos', 'emergency_contact_mobile')) $table->string('emergency_contact_mobile', 20)->nullable();
            if (!Schema::hasColumn('pos', 'bank_name')) $table->string('bank_name')->nullable();
            if (!Schema::hasColumn('pos', 'bank_account_number')) $table->string('bank_account_number', 100)->nullable();
            if (!Schema::hasColumn('pos', 'bank_ifsc_code')) $table->string('bank_ifsc_code', 50)->nullable();
            if (!Schema::hasColumn('pos', 'documents')) $table->json('documents')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            foreach (['date_of_joining', 'date_of_birth', 'gender', 'salary', 'address', 'emergency_contact_name', 'emergency_contact_mobile', 'bank_name', 'bank_account_number', 'bank_ifsc_code', 'documents'] as $column) {
                if (Schema::hasColumn('pos', $column)) $table->dropColumn($column);
            }
        });
    }
};
