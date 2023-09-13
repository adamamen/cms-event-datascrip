<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_visitor_event', function (Blueprint $table) {
            $table->id('id');
            $table->string('event_id');
            $table->string('ticket_no');
            $table->timestamp('registration_date');
            $table->string('full_name');
            $table->string('address');
            $table->string('email');
            $table->string('mobile');
            $table->timestamp('created_at')->nullable();
            $table->string('created_by');
            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by');
            $table->string('no_invoice');
            $table->string('jenis_event');
            $table->string('status_pembayaran');
            $table->string('metode_bayar');
            $table->string('sn_product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
