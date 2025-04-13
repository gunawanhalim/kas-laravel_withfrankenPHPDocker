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
        Schema::create('roles_permission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->json('akun_kas');
            $table->json('subcategories');
            $table->json('kas_bank');
            $table->json('pelanggan');
            $table->json('penjualan');
            $table->json('piutang');
            $table->json('users');
            $table->json('roles_permission');

            
            $table->foreign('id_user')
            ->references('id')
            ->on('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_permission');
    }
};
