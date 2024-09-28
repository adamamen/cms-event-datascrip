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
            $table->string('barcode_no');
            $table->timestamp('scan_date');
            $table->string('source');
            $table->string('gender');
            $table->string('account_instagram');
            $table->string('type_invitation');
            $table->string('invitation_name');
            $table->string('barcode_link');
            $table->string('noted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_visitor_event', function (Blueprint $table) {
            $table->dropColumn('barcode_no');
            $table->dropColumn('scan_date');
        });
    }
};
