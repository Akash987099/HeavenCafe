<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->string('payment_gateway', 30)->nullable()->after('payment_status');
            $table->string('payu_txnid', 50)->nullable()->unique()->after('payment_gateway');
            $table->string('payu_payment_id', 100)->nullable()->after('payu_txnid');
            $table->string('payment_method', 50)->nullable()->after('payu_payment_id');
            $table->json('payu_response')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('customer_orders', function (Blueprint $table) {
            $table->dropUnique(['payu_txnid']);
            $table->dropColumn(['payment_gateway', 'payu_txnid', 'payu_payment_id', 'payment_method', 'payu_response']);
        });
    }
};
