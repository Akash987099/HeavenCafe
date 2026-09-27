<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos_order', function (Blueprint $table) {
            if (! Schema::hasColumn('pos_order', 'store_id')) $table->unsignedBigInteger('store_id')->nullable()->index();
            if (! Schema::hasColumn('pos_order', 'payment_gateway')) $table->string('payment_gateway', 30)->nullable();
            if (! Schema::hasColumn('pos_order', 'payu_txnid')) $table->string('payu_txnid', 50)->nullable()->unique();
            if (! Schema::hasColumn('pos_order', 'payu_payment_id')) $table->string('payu_payment_id', 100)->nullable();
            if (! Schema::hasColumn('pos_order', 'payu_response')) $table->json('payu_response')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pos_order', function (Blueprint $table) {
            if (Schema::hasColumn('pos_order', 'payu_response')) $table->dropColumn('payu_response');
            if (Schema::hasColumn('pos_order', 'payu_payment_id')) $table->dropColumn('payu_payment_id');
            if (Schema::hasColumn('pos_order', 'payu_txnid')) $table->dropUnique(['payu_txnid']);
            if (Schema::hasColumn('pos_order', 'payu_txnid')) $table->dropColumn('payu_txnid');
            if (Schema::hasColumn('pos_order', 'payment_gateway')) $table->dropColumn('payment_gateway');
            if (Schema::hasColumn('pos_order', 'store_id')) $table->dropColumn('store_id');
        });
    }
};
