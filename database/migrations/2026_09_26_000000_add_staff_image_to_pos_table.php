<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (!Schema::hasColumn('pos', 'staff_image')) {
                $table->string('staff_image')->nullable()->after('bank_ifsc_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (Schema::hasColumn('pos', 'staff_image')) {
                $table->dropColumn('staff_image');
            }
        });
    }
};
