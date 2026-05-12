<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->boolean('owner_finished')->default(false)->after('id_umkm');
            $table->boolean('employee_finished')->default(false)->after('owner_finished');
            $table->unsignedBigInteger('id_owner')->nullable()->after('employee_finished');
            $table->unsignedBigInteger('id_employee')->nullable()->after('id_owner');
            $table->json('answers')->nullable()->after('id_employee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropColumn(['owner_finished', 'employee_finished', 'id_owner', 'id_employee', 'answers']);
        });
    }
};
