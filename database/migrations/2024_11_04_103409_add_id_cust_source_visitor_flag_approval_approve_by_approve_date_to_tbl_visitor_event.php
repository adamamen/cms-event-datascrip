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
        Schema::table('tbl_visitor_event', function (Blueprint $table) {
            $table->bigInteger('id_cust')->unsigned()->nullable();
            $table->string('source_visitor', 10)->nullable();
            $table->boolean('flag_approval')->nullable();
            $table->string('approve_by', 255)->nullable();
            $table->dateTime('approve_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_visitor_event', function (Blueprint $table) {
            $table->dropColumn(['id_cust', 'source_visitor', 'flag_approval', 'approve_by', 'approve_date']);
        });
    }
};
