<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
// php artisan migrate:refresh  --path=/database/migrations/2024_05_17_071011_create_kas_bank_table.php
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kas_bank', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_bukti')->index();
            $table->string('nama_akun')->nullable();
            $table->string('from')->nullable();
            $table->string('nomor_bukti')->nullable();
            $table->unsignedBigInteger('from_account_id')->nullable();
            $table->unsignedBigInteger('to_account_id')->nullable();
            $table->unsignedBigInteger('subcategories_id');
            $table->string('kategori');
            $table->bigInteger('jumlah');
            $table->string('nama_pelanggan')->length('50')->nullable();
            $table->string('nama_sales_utang')->length('50')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('nama_user');
            $table->dateTime('tanggal_log');
            
            // Kolom tambahan untuk menandai dari mana transfer berasal
            // $table->string('transfer_from')->nullable();
            
            $table->foreign('from_account_id')
                ->references('id')
                ->on('akun_kas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
    
            $table->foreign('to_account_id')
                ->references('id')
                ->on('akun_kas')
                ->onDelete('cascade')
                ->onUpdate('cascade');
    
            $table->foreign('subcategories_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
    
            $table->index(['from_account_id', 'to_account_id']);
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kas_bank');
    }
};
