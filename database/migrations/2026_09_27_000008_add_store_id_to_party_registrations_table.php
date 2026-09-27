<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('party_registrations', function (Blueprint $table) {
            if (! Schema::hasColumn('party_registrations', 'store_id')) {
                $table->unsignedBigInteger('store_id')->nullable()->after('party_master_id');
                $table->index('store_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('party_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('party_registrations', 'store_id')) {
                $table->dropIndex(['store_id']);
                $table->dropColumn('store_id');
            }
        });
    }
};
