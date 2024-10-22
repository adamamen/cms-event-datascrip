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
        Schema::create('mst_cust', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('gender', 2);
            $table->string('email');
            $table->string('institution');
            $table->string('name_institution');
            $table->string('phone_no');
            $table->string('city');
            $table->string('participant_type');
            $table->bigInteger('id_divisi');
            $table->string('name_divisi');
            $table->string('upload_by');
            $table->dateTime('upload_date');
            $table->string('update_by');
            $table->dateTime('update_date');
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
