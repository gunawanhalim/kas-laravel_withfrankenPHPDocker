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
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('nomor_nota')->primary();
            $table->timestamp('tanggal_nota');
            $table->string('nama_pelanggan');
            $table->string('alamat');
            $table->bigInteger('total');
            $table->string('nama_sales');
            $table->string('nama_user');
            $table->datetime('tanggal_log');

            $table->dateTime('jatuh_tempo')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
