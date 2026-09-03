<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_subcategory', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->primary(['category_id', 'sub_category_id']);
            $table->foreign('category_id')->references('id')->on('category')->cascadeOnDelete();
            $table->foreign('sub_category_id')->references('id')->on('sub_category')->cascadeOnDelete();
        });

        DB::table('sub_category')->select('id', 'category_id')->orderBy('id')->each(function ($subcategory) {
            $categoryIds = collect(explode(',', (string) $subcategory->category_id))
                ->map(fn ($categoryId) => trim($categoryId))
                ->filter(fn ($categoryId) => ctype_digit($categoryId))
                ->unique();

            foreach ($categoryIds as $categoryId) {
                DB::table('category_subcategory')->insertOrIgnore([
                    'category_id' => (int) $categoryId,
                    'sub_category_id' => $subcategory->id,
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_subcategory');
    }
};
