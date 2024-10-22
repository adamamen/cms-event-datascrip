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
        Schema::create('log_send_email_cust', function (Blueprint $table) {
            $table->bigInteger('id');
            $table->bigInteger('id_cust');
            $table->string('name_cust');
            $table->dateTime('send_email_date');
            $table->string('send_email_by');
            $table->bigInteger('id_event');
            $table->string('title_event');
            $table->bigInteger('id_divisi');
            $table->string('name_divisi');
            $table->string('email_to');
            $table->text('body_email');
            $table->boolean('flag_email');
            $table->string('email_type');
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
