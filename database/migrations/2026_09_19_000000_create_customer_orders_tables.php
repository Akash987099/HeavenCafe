<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customer_orders')) {
            Schema::create('customer_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                // 191 keeps the unique index compatible with older MySQL installations.
                $table->string('order_number', 191);
                $table->string('customer_name');
                $table->string('customer_mobile', 20)->nullable();
                $table->string('status', 50)->default('pending');
                $table->decimal('subtotal', 12, 2);
                $table->decimal('grand_total', 12, 2);
                $table->timestamps();
            });
        }

        // This also makes a previously interrupted migration safe to resume.
        DB::statement('ALTER TABLE `customer_orders` MODIFY `order_number` VARCHAR(191) NOT NULL');
        DB::statement("ALTER TABLE `customer_orders` MODIFY `status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
        $this->addIndexIfMissing('customer_orders', 'customer_orders_store_id_index', 'INDEX', 'store_id');
        $this->addIndexIfMissing('customer_orders', 'customer_orders_order_number_unique', 'UNIQUE', 'order_number');
        $this->addIndexIfMissing('customer_orders', 'customer_orders_status_index', 'INDEX', 'status');

        if (!Schema::hasTable('customer_order_items')) {
            Schema::create('customer_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('customer_order_id')->constrained('customer_orders')->cascadeOnDelete();
                $table->unsignedBigInteger('product_id')->index();
                $table->string('product_name');
                $table->decimal('price', 12, 2);
                $table->unsignedInteger('quantity');
                $table->decimal('total', 12, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_order_items');
        Schema::dropIfExists('customer_orders');
    }

    private function addIndexIfMissing(string $table, string $index, string $type, string $column): void
    {
        if (empty(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]))) {
            DB::statement("ALTER TABLE `{$table}` ADD {$type} `{$index}` (`{$column}`)");
        }
    }
};
