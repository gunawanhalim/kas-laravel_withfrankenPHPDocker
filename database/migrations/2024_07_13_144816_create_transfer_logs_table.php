<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferLogsTable extends Migration
{
    public function up()
    {
        Schema::create('transfer_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_account');
            $table->foreign('from_account')->references('id')->on('kas_bank')->onDelete('cascade');
            $table->unsignedBigInteger('to_account');
            $table->foreign('to_account')->references('id')->on('kas_bank')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transfer_logs');
    }
}
