<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_attendances', function (Blueprint $table) {

            $table->enum('work_mode', ['office', 'remote', 'paid_leave'])->default('office')->after('check_out_time');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_attendances', function (Blueprint $table) {
             $table->dropColumn('work_mode');
        });
    }
};
