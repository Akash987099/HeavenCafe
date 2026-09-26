<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_tasks', function (Blueprint $table) {
            if (Schema::hasIndex('staff_tasks', 'staff_tasks_staff_status_index')) {
                $table->dropIndex('staff_tasks_staff_status_index');
            }
            if (Schema::hasIndex('staff_tasks', 'staff_tasks_user_assigned_date_index')) {
                $table->dropIndex('staff_tasks_user_assigned_date_index');
            }

            foreach (['assigned_date', 'due_date', 'status'] as $column) {
                if (Schema::hasColumn('staff_tasks', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('staff_tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('staff_tasks', 'assigned_date')) {
                $table->date('assigned_date')->nullable();
            }
            if (! Schema::hasColumn('staff_tasks', 'due_date')) {
                $table->date('due_date')->nullable();
            }
            if (! Schema::hasColumn('staff_tasks', 'status')) {
                $table->string('status', 30)->default('pending');
            }
        });
    }
};
