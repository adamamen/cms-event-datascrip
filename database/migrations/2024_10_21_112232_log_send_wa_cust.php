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
        Schema::create('log_send_wa_cust', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('id_cust');
            $table->string('name_cust');
            $table->dateTime('send_wa_date');
            $table->string('send_wa_by');
            $table->bigInteger('id_event');
            $table->string('title_event');
            $table->bigInteger('id_divisi');
            $table->string('no_ponsel');
            $table->text('body_wa');
            $table->boolean('flag_wa');
            $table->string('wa_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
