<?php
// php artisan migrate:refresh  --path=/database/migrations/2024_05_17_071016_create_piutang_table.php
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
        Schema::create('piutang', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_bukti');
            $table->dateTime('tanggal_bukti');
            $table->string('nomor_nota');
            $table->bigInteger('jumlah');
            $table->string('kategori');
            $table->string('nama_akun')->nullable();
            $table->string('nama_pelanggan');
            $table->string('nama_user');
            $table->dateTime('tanggal_log');
            
            $table->dateTime('jatuh_tempo')->nullable();


            // $table->foreign('nama_akun')
            //         ->references('nama_akun')
            //         ->on('akun_kas')
            //         ->onDelete('cascade')
            //         ->onUpdate('cascade');
            $table->foreign('kategori')
                    ->references('name')
                    ->on('categori_suppliers')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');                   
            $table->foreign('nomor_nota')
                    ->references('nomor_nota')
                    ->on('penjualan')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            // $table->foreign('tanggal_bukti')
            //         ->references('tanggal_bukti')
            //         ->on('kas_bank')
            //         ->onDelete('cascade')
            //         ->onUpdate('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutang');

    }
};
