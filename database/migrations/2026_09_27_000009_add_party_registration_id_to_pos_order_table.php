<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_order', function (Blueprint $table) {
            if (! Schema::hasColumn('pos_order', 'party_registration_id')) {
                $table->unsignedBigInteger('party_registration_id')->nullable()->after('store_id');
                $table->index('party_registration_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pos_order', function (Blueprint $table) {
            if (Schema::hasColumn('pos_order', 'party_registration_id')) {
                $table->dropIndex(['party_registration_id']);
                $table->dropColumn('party_registration_id');
            }
        });
    }
};
