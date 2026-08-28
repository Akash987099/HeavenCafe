<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('products', 'client_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedBigInteger('client_id')->nullable()->after('id')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('products', 'client_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex(['client_id']);
                $table->dropColumn('client_id');
            });
        }
    }
};
