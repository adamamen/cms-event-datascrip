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
            $table->boolean('flag_whatsapp')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_visitor_event', function (Blueprint $table) {
            $table->dropColumn('flag_whatsapp');
        });
    }
};
