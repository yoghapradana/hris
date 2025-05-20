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
            // Image paths
            $table->string('check_in_img_path')->nullable()->after('check_in_time');
            $table->string('check_out_img_path')->nullable()->after('check_out_time');
            
            // Location coordinates - ~11cm precision
            $table->decimal('check_in_latitude', 10, 6)->nullable()->after('check_in_img_path');
            $table->decimal('check_in_longitude', 11, 6)->nullable()->after('check_in_latitude');
            $table->decimal('check_out_latitude', 10, 6)->nullable()->after('check_out_img_path');
            $table->decimal('check_out_longitude', 11, 6)->nullable()->after('check_out_latitude');
            
            // IP addresses
            $table->string('check_in_ip', 45)->nullable()->after('check_in_longitude');
            $table->string('check_out_ip', 45)->nullable()->after('check_out_longitude');
            
            // Approval fields
            $table->string('approval_status', 20)->default('pending')->after('check_out_ip');
            $table->unsignedBigInteger('approver_id')->nullable()->after('approval_status');
            $table->timestamp('approval_timestamp')->nullable()->after('approver_id');
            
            // Foreign key for approver
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_attendances', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            
            $table->dropColumn([
                'check_in_img_path',
                'check_out_img_path',
                'check_in_latitude',
                'check_in_longitude',
                'check_out_latitude',
                'check_out_longitude',
                'check_in_ip',
                'check_out_ip',
                'approval_status',
                'approver_id',
                'approval_timestamp'
            ]);
        });
    }
};
