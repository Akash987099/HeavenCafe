<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('party_masters', function (Blueprint $table) {
            if (! Schema::hasColumn('party_masters', 'description')) $table->text('description')->nullable()->after('slug');
            if (! Schema::hasColumn('party_masters', 'image')) $table->string('image', 255)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('party_masters', function (Blueprint $table) {
            if (Schema::hasColumn('party_masters', 'image')) $table->dropColumn('image');
            if (Schema::hasColumn('party_masters', 'description')) $table->dropColumn('description');
        });
    }
};
