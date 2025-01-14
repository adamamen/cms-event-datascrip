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
        Schema::create('tbl_access_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_divisi');
            $table->string('nama_divisi', 100);
            $table->unsignedBigInteger('id_divisi_owner');
            $table->string('nama_divisi_owner', 100);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status', 100);
            $table->string('created_by')->nullable();
            $table->timestamp('created_date')->nullable();
            $table->string('update_by')->nullable();
            $table->timestamp('update_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_access_user');
    }
};
