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
        Schema::create('tbl_master_event', function (Blueprint $table) {
            $table->id('id_event');
            $table->string('status');
            $table->string('title');
            $table->text('desc');
            $table->string('company');
            $table->timestamp('start_event');
            $table->timestamp('end_event');
            $table->string('logo');
            $table->string('location');
            $table->timestamp('created_at')->nullable();
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
