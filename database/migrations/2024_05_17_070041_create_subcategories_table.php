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
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            // $table->string('nama_akun');
            $table->string('name')->unique();

            
            $table->foreign('kategori_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade');
                    
            // $table->foreign('nama_akun')
            //         ->references('nama_akun')
            //         ->on('akun_kas')
            //         ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcategories');
    }
};
