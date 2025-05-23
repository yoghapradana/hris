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
        Schema::table('user_attendances', function (Blueprint $table) {
            $table->renameColumn('approver_id', 'approved_by');
            $table->renameColumn('approval_timestamp', 'approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_attendances', function (Blueprint $table) {
            $table->renameColumn('approver_id', 'approved_by');
            $table->renameColumn('approval_timestamp', 'approved_at');
        });
    }
};
