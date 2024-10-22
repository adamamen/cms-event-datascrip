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
        Schema::create('tbl_send_email_cust', function (Blueprint $table) {
            $table->integer('id');
            $table->text('content');
            $table->string('type');
            $table->dateTime('created_at');
            $table->string('created_by');
            $table->dateTime('updated_at');
            $table->string('updated_by');
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
