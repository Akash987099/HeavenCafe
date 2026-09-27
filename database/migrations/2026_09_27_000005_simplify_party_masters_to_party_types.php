<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('party_masters', function (Blueprint $table) {
            $table->dropIndex('party_masters_party_type_status_index');
            $table->dropIndex('party_masters_event_date_index');
            $table->dropColumn(['party_type', 'contact_person', 'mobile', 'email', 'event_date', 'guest_count', 'address', 'notes']);
        });

        Schema::table('party_masters', function (Blueprint $table) {
            $table->renameColumn('party_name', 'name');
        });

        Schema::table('party_masters', function (Blueprint $table) {
            $table->string('slug', 160)->unique()->after('name');
            $table->index(['name', 'status']);
        });

        DB::table('party_masters')->insert([
            ['name' => 'Birthday Party', 'slug' => 'birthday-party', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Marriage Party', 'slug' => 'marriage-party', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'School Party', 'slug' => 'school-party', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other Party', 'slug' => 'other-party', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('party_masters', function (Blueprint $table) {
            $table->dropIndex('party_masters_name_status_index');
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
            $table->renameColumn('name', 'party_name');
            $table->string('party_type', 30)->default('other');
            $table->string('contact_person', 150)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedInteger('guest_count')->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->index(['party_type', 'status']);
            $table->index('event_date');
        });
    }
};
