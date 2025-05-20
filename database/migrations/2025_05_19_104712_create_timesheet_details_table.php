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
        Schema::create('timesheet_details', function (Blueprint $table) {
            $table->id();
            $table->string('timesheet_id');
            $table->time('time_start');
            $table->time('time_end');
            $table->time('total_time')->nullable();
            $table->string('job_code')->nullable();
            $table->text('job_descriptions')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('timesheet_id')->references('id')->on('timesheets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet_details');
    }
};
