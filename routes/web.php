<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\master_kas_controller;
use App\Http\Controllers\master_piutang_controller;
use App\Http\Controllers\kas_bankController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelunasanController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UtangController;
use App\Http\Controllers\SupplierController;


// Route::get('/testing', function () {
//     return view('/exports.reports');
// });

// Route::get('/invoice', function () {
//     return view('/eInvoice.eInvoice');
// });
Route::get('/test-db', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        return "✅ Database Connected!";
    } catch (\Exception $e) {
        return "❌ DB Error: " . $e->getMessage();
    }
});
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login?pengguna');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::view('/auth/disabled', 'auth/disabled')->name('Auth.disabled');

Route::middleware(['auth', 'HandleMaxExecutionTime', 'checkStatus'])->group(function () // Index
{

    // User

    Route::get('/user-edit-detail', [AuthController::class, 'editDetail'])->name('userDetail.edit');
    Route::put('/editUser/{id}', [AuthController::class, 'updateUser'])->name('editUser.update');
    // akun_kas
    
    Route::get('/beranda.php', [master_kas_controller::class, 'sidebarindex']);
    Route::get('/report_daily', [master_kas_controller::class, 'dailyChart'])->name('report_daily');
    Route::get('/report_daily/get', [master_kas_controller::class, 'getDailyReport']);
    Route::get('/chart-data-daily', [master_kas_controller::class, 'getData']);



    // Piutang Utang
    Route::get('/credit.php', [master_piutang_controller::class, 'credit'])->name('credit');
    Route::get('/showCredit.php/{nama_akun}', [master_piutang_controller::class, 'showCredit'])->name('credit.show');

    Route::get('/debit.php', [master_piutang_controller::class, 'debit'])->name('debit');
    Route::get('/showDebit.php/{nama_akun}', [master_piutang_controller::class, 'showDebit'])->name('debit.show');

    // Piutang
    Route::get('/piutang.php', [master_piutang_controller::class, 'index'])->name('piutang.index');

    // Penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');

    // Utang
    Route::get('/utang.php', [UtangController::class, 'index'])->name('utang.index');

    // Pembelian
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian.index');


    // Kas Bank
    Route::get('/transactions.php', [kas_bankController::class, 'index'])->name('kas_bank.index');
    Route::get('/transactions/{id}', [kas_bankController::class, 'show'])->name('kas_bank.show');

    // Kategori and subcategories

    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/showKategori', [KategoriController::class, 'show'])->name('kategori.show');

    // buku kas

    Route::get('/bukukas', [master_kas_controller::class, 'index'])->name('bukukas.index');

    // Link Kateogri Utang

    Route::get('/link-kategori/{category}', [master_kas_controller::class, 'linkCategoriHutang'])->name('link-kategori');

    // Detail Kategori Nomor Bukti
    Route::get('/pinjaman-kategoriDetail/{nomor_bukti}', [PembelianController::class, 'detailCategoriHutang'])->name('detail-kategori');
    Route::get('/pembayaran-kategoriDetail/{nomor_bukti}', [PenjualanController::class, 'detailCategoriPiutang'])->name('detail-kategoriPiutang');

    // Link Detail Penjualan Nama Pelanggan
    Route::get('/detail-penjualan-pelanggan/nama_pelanggan={pelanggan}&nomor_bukti={nomor_bukti}', [PelangganController::class, 'linkDetailPenjualan'])->name('link-pelanggan-piutang');
    // Link Detail Pembelian Nama Supplier
    Route::get('/detail-pembelian-pelanggan/nama_supplier={sales}&nomor_bukti={nomor_bukti}', [PelangganController::class, 'linkDetailPembelian'])->name('link-sales-utang');
    // Link Kategori Piutang

    Route::get('/link-kategori-piutang/{category}', [master_kas_controller::class, 'linkCategoriPiutang'])->name('link-kategori-piutang');
    // Pembayaran

    Route::get('/detailNomorBukti/{nomor_bukti}', [master_piutang_controller::class, 'detailPembayaran'])->name('pembayaran.detail');

    // Perutangan
    Route::get('/detail-perutangan/{nomor_bukti}', [UtangController::class, 'detailPerutangan'])->name('perutangan.detail');

    // History Transfer
    Route::get('/detail-transfer', [TransferController::class, 'index'])->name('transfer.index');

    // PDF, Word, Excel

    Route::get('/chart-pdf', [Controller::class, 'chartPDF']);
    Route::get('/export-pdf', [Controller::class, 'exportPDF']);
    Route::get('/export-doc', [Controller::class, 'exportDOC']);
    Route::get('/export-excel', [Controller::class, 'exportExcel']);

    Route::get('/kas-pdf', [Controller::class, 'kasPDF']);
    Route::get('/kas-doc', [Controller::class, 'kasDOC']);
    Route::get('/kas-excel', [Controller::class, 'kasExcel']);

    Route::get('/report_daily/download', [master_kas_controller::class, 'dailyDownload']);


    // Search GET

    // generate nomor nota
    Route::get('/addPenjualan/generate-nomor-nota', [PenjualanController::class, 'generateNomorNotaJson']);
    Route::get('/addPembelian/generate-nomor-nota', [PembelianController::class, 'generateNomorNotaJson']);

    Route::get('/items/delete', [TransferController::class, 'deleteSelected']);

    Route::get('/searchKas', [kas_bankController::class, 'searchKas'])->name('searchKas');
    Route::get('/nama_pelangganSearch', [PenjualanController::class, 'searchPelanggan'])->name('searchPelanggan');
    Route::get('/nomorBuktiPiutang', [PelangganController::class, 'searchPelangganPiutang'])->name('searchPelangganPiutang');
    Route::get('/nomorBuktiUtang', [PelangganController::class, 'searchPelangganUtang'])->name('searchPelangganUtang');
    Route::get('/pelangganPembeliSearch', [PelangganController::class, 'searchPelanggan'])->name('pelangganPembeli.search');
    Route::get('/nomorNotaSearch', [PenjualanController::class, 'searchNomorNota'])->name('searchNomorNota');
    Route::get('/searchKategori', [PenjualanController::class, 'searchKategori'])->name('searchKategori');

    Route::get('/searchKategoriPengeluaran', [KategoriController::class, 'searchKategoriPengeluaran'])->name('searchKategoriPengeluaran');
    Route::get('/searchKategoriPemasukan', [KategoriController::class, 'searchKategoriPemasukan'])->name('searchKategoriPemasukan');

    Route::get('/getTotalKas', [kas_bankController::class, 'getKas'])->name('get.kas');
    Route::get('/kas-data', [kas_bankController::class, 'kasData'])->name('data.kas');
    Route::get('/page-view-data', [kas_bankController::class, 'PageViewData'])->name('pageView.kas');
    // Route::get('/pembayaranDetail/{nomorBukti}',[master_piutang_controller::class,'pembayaranDetail'])->name('pembayaran.detail');


    // e invoice
    Route::get('/e-invoice', [Controller::class, 'eInvoice'])->name('invoice.add');
});

Route::middleware(['auth', 'HandleMaxExecutionTime', 'MustAdmin', 'MustOwnerManager', 'checkStatus'])->group(function () //Post,delete,put
{

    // Master User
    Route::get('/userDetail/{idUser}', [AuthController::class, 'show'])->name('user.detail');

    // Master Akun Kas

    Route::get('/akun_kas_edit/{id}', [master_kas_controller::class, 'update'])->name('akun_kas.edit');
    Route::get('/akun_kas/{nama_akun}', [master_kas_controller::class, 'show'])->name('akun_kas.show');
    // Route::get('/akun_kas/{id}', [master_kas_controller::class, 'destroyKasBank'])->name('akun_kas.destroy');
    Route::get('/get-subcategories/{type}', [master_kas_controller::class, 'getSubcategories']);
    Route::post('/addKas', [master_kas_controller::class, 'store'])->name('addKas.store');
    Route::post('/addKas/{id}', [master_kas_controller::class, 'update'])->name('addKas.update');
    Route::delete('/deleteKas/{id}', [master_kas_controller::class, 'destroy'])->name('kas.destroy');

    // Credit and Debit
    Route::get('/generate-nomor-bukti', [master_piutang_controller::class, 'generateNomorBukti']);

    // Debit
    Route::get('/debitDetail/{nomorBukti}', [master_piutang_controller::class, 'detailDebit'])->name('piutang.detail');
    Route::get('/addDebit/{nama_akun}', [master_piutang_controller::class, 'createDebit'])->name('debit.add');
    Route::post('/debitStore', [master_piutang_controller::class, 'debitStore'])->name('debit.store');
    // Route::get('/editDebit/{nomor_bukti}',[master_piutang_controller::class,'editDebit'])->name('piutang.edit');
    Route::put('/debit/{nomorBukti}', [master_piutang_controller::class, 'updateDebit'])->name('debit.update');
    Route::delete('/destroy/{nomor_bukti}', [master_piutang_controller::class, 'destroyDebit'])->name('debit.destroy');
    // Credit
    Route::get('/addCredit/{nama_akun}', [master_piutang_controller::class, 'createCredit'])->name('credit.add');
    Route::post('/creditStore', [master_piutang_controller::class, 'creditStore'])->name('credit.store');
    Route::get('/editCredit/{nomor_bukti}', [master_piutang_controller::class, 'editCredit'])->name('credit.edit');
    Route::put('/credit/{nomorBukti}', [master_piutang_controller::class, 'updateCredit'])->name('credit.update');
    Route::delete('/destroyCredit/{nomor_bukti}', [master_piutang_controller::class, 'destroyCredit'])->name('credit.destroy');

    // Kas Bank

    Route::get('/transactions-edit/{id}', [kas_bankController::class, 'edit'])->name('kas_bank.edit');
    Route::put('/transactions-update/{id}', [kas_bankController::class, 'update'])->name('kas_bank.update');
    Route::post('/transactions.php/income', [kas_bankController::class, 'store'])->name('kas_bank.storeIncome');
    Route::post('/transactions.php/expense', [kas_bankController::class, 'store'])->name('kas_bank.storeExpense');
    Route::delete('/transactions/{id}', [kas_bankController::class, 'destroy'])->name('kas_bank.destroy');

    Route::get('transfers-edit/{id}', [kas_bankController::class, 'editTransfer'])->name('transfers.edit');
    Route::post('transfers/{id}', [kas_bankController::class, 'updateTransfer'])->name('transfers.update');
    Route::delete('transfers/{id}', [kas_bankController::class, 'destroyTransfer'])->name('transfers.destroy');

    Route::post('transfer', [kas_bankController::class, 'transfer'])->name('kas_bank.transfer');


    // Pelanggan Penjualan
    Route::get('/pelangganPenjualan', [PelangganController::class, 'indexPelanggan'])->name('pelangganPenjualan.index');
    Route::post('/pelangganStore.php', [master_kas_controller::class, 'addPelangganstore'])->name('addPelanggan.store');


    // Pelanggan Pembelian
    Route::get('/pelangganPembelian', [PelangganController::class, 'indexPembelian'])->name('pelangganPembelian.index');
    Route::post('/pelangganStorePembelian.php', [PelangganController::class, 'addPelangganstorePembelian'])->name('addPelangganPembelian.store');
    Route::post('/editPelangganPembelian/{id}', [PelangganController::class, 'updatePelangganPembelian'])->name('editPelangganPembelian.update');
    Route::delete('/deletePelangganPembelian/{id}', [PelangganController::class, 'destroyPelangganPembelian'])->name('deletePelangganPembelian.destroy');



    // Kategori and subcategories
    Route::post('/addSubcategories', [KategoriController::class, 'store'])->name('addSubcategories.store');
    Route::put('/editSubcategories/{id}', [KategoriController::class, 'update'])->name('editSubcategories.update');
    Route::delete('/deleteSubcategories/{id}', [KategoriController::class, 'destroy'])->name('deleteSubcategories.destroy');
    Route::put('/moveSubcategories/{id}', [KategoriController::class, 'moveSubcategories'])->name('moveSubcategories.destroy');

    // Supplier
    Route::post('/addCategoriSupplier', [SupplierController::class, 'store'])->name('addCategori.store');
    Route::put('/editCategoriSupplier/{id}', [SupplierController::class, 'update'])->name('editCategori.update');
    Route::delete('/deleteCategoriSupplier/{id}', [SupplierController::class, 'destroy'])->name('deleteCategori.destroy');
    Route::put('/moveCategoriSupplier/{id}', [SupplierController::class, 'moveSubcategories'])->name('moveCategori.destroy');

    // Penjualan
    Route::get('/addPenjualan', [PenjualanController::class, 'create'])->name('penjualan.add');
    Route::get('/detailPenjualan/{nomor_nota}', [PenjualanController::class, 'show'])->name('penjualan.detail');
    Route::get('/addPenjualan', [PenjualanController::class, 'create'])->name('penjualan.add');
    Route::post('/addPenjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

    // e Invoice

    Route::get('/e-Invoice', [Controller::class, 'invoice'])->name('eInvoice.create');
    Route::post('/ekspor_pdf/invoice.php', [Controller::class, 'invoicePDF'])->name('eInvoice.ekspor');


    // Piutang
    Route::get('/addPiutang', [master_piutang_controller::class, 'create'])->name('piutang.create');
    Route::post('/addPiutang', [master_piutang_controller::class, 'store'])->name('piutang.store');
    Route::get('/editPiutang/{nomorBukti}', [master_piutang_controller::class, 'edit'])->name('piutang.edit');
    Route::put('/editPiutang/{nomorBukti}', [master_piutang_controller::class, 'update'])->name('piutang.update');

    // Pembayaran
    Route::get('/pembayaran-detail/{id}', [master_piutang_controller::class, 'detailRincianPembayaran'])->name('pembayaran.rinci');
    Route::get('/pembayaran-add/{nomor_bukti}', [master_piutang_controller::class, 'createPembayaran'])->name('pembayaran.create');
    Route::get('/pembayaran-bayar/{nomor_bukti}', [master_piutang_controller::class, 'createPembayaranBayar'])->name('pembayaran.createbayar');
    Route::get('/pembayaran-tambah/{nomor_bukti}', [master_piutang_controller::class, 'createPembayaranTambah'])->name('pembayaran.createtambah');
    Route::post('/pembayaran-add', [master_piutang_controller::class, 'storePembayaran'])->name('pembayaran.store');
    Route::post('/pembayaran-bayar', [master_piutang_controller::class, 'storePembayaranBayar'])->name('pembayaran.storebayar');
    Route::post('/pembayaran-tambah', [master_piutang_controller::class, 'storePembayaranTambah'])->name('pembayaran.storetambah');

    Route::get('/pembayaran-edit/{id}', [master_piutang_controller::class, 'editPembayaran'])->name('pembayaran.edit');
    Route::put('/pembayaran-update/{id}', [master_piutang_controller::class, 'updatePembayaran'])->name('pembayaran.update');
    Route::delete('/pembayaran-delete/{id}', [master_piutang_controller::class, 'destroyPembayaran'])->name('pembayaran.destroy');
    Route::delete('/pembayaran-delete-kategori/{id}', [master_piutang_controller::class, 'destroyPembayaranKategori'])->name('pembayaran.destroykategori');

    // Utang
    Route::get('/detail-utang/{nomorBukti}', [UtangController::class, 'utangDetail'])->name('utang.detail');


    // Pembelian
    Route::get('/pembelian-add', [PembelianController::class, 'create'])->name('pembelian.add');
    Route::get('/detailPembelian/{nomor_nota}', [PembelianController::class, 'show'])->name('pembelian.detail');
    Route::post('/addPembelian', [PembelianController::class, 'store'])->name('pembelian.store');


    // Pelunasan
    Route::get('/pinjaman-detail/{id}', [UtangController::class, 'detailRincianPinjaman'])->name('pinjaman.rinci');
    Route::get('/pinjaman-add/{nomor_bukti}', [UtangController::class, 'createPinjaman'])->name('pinjaman.create');
    Route::get('/pinjaman-tambah/{nomor_bukti}', [UtangController::class, 'createPinjamanTambah'])->name('pinjaman.createtambah');
    Route::get('/pinjaman-bayar/{nomor_bukti}', [UtangController::class, 'createPinjamanBayar'])->name('pinjaman.bayar');
    Route::post('/pinjaman-add', [UtangController::class, 'storePinjaman'])->name('pinjaman.store');
    Route::post('/pinjaman-tambah', [UtangController::class, 'storePinjamanTambah'])->name('pinjaman.storetambah');
    Route::post('/pinjaman-bayar', [UtangController::class, 'storePinjamanBayar'])->name('pinjaman.storebayar');

    // Pelanggan Piutang
    Route::get('/pelangganPenjualan-bayar', [PelangganController::class, 'createPembayaranBayar'])->name('pelanggan.createbayar');
    Route::post('/pelangganPenjualan-bayar', [PelangganController::class, 'storePembayaranBayar'])->name('pelanggan.storebayar');

    Route::get('/pelangganPenjualan-tambah', [PelangganController::class, 'createPembayaranTambah'])->name('pelanggan.createtambah');
    Route::post('/pelangganPenjualan-tambah', [PelangganController::class, 'storePembayaranTambah'])->name('pelanggan.storetambah');

    // Pelanggan Utang
    Route::get('/pelangganPembelian-bayar', [PelangganController::class, 'createPembelianBayar'])->name('pelangganPembelian.createbayar');
    Route::post('/pelangganPembelian-bayar', [PelangganController::class, 'storePembelianBayar'])->name('pelangganPembelian.storebayar');

    Route::get('/pelangganPembelian-tambah', [PelangganController::class, 'createPembelianTambah'])->name('pelangganPembelian.createtambah');
    Route::post('/pelangganPembelian-tambah', [PelangganController::class, 'storePembelianTambah'])->name('pelangganPembelian.storetambah');

    // Pelunasan
    Route::get('/pelunasan-piutang', [PelunasanController::class, 'indexPiutang'])->name('pelunasan.piutang');
    Route::get('/pelunasan-utang', [PelunasanController::class, 'indexUtang'])->name('pelunasan.utang');
});

Route::middleware(['auth', 'HandleMaxExecutionTime', 'MustOwnerManager', 'checkStatus'])->group(function () //Post,delete,put
{
    // Pelanggan Piutang
    Route::get('/pelangganPenjualan-edit/{id}', [PelangganController::class, 'editPembayaran'])->name('pelangganPiutang.edit');
    Route::put('/pelangganPenjualan-update/{id}', [PelangganController::class, 'updatePembayaran'])->name('pelangganPiutang.update');

    Route::delete('/pelangganPenjualan-delete/{id}', [PelangganController::class, 'destroyPembayaran'])->name('pelangganPiutang.destroy');
    // Pelanggan Pembelian Edit, Delete
    Route::get('/pelangganPembelian-edit/{id}', [PelangganController::class, 'editPembelian'])->name('pelangganUtang.edit');
    Route::put('/pelangganPembelian-update/{id}', [PelangganController::class, 'updatePembelian'])->name('pelangganUtang.update');

    Route::delete('/pelangganPembelian-delete/{id}', [PelangganController::class, 'destroyPembelian'])->name('pelangganUtang.destroy');

    // Pelunasan
    Route::get('/pinjaman-edit/{id}', [UtangController::class, 'editPinjaman'])->name('pinjaman.edit');
    Route::put('/pinjaman-update/{id}', [UtangController::class, 'updatePinjaman'])->name('pinjaman.update');
    Route::delete('/pinjaman-delete/{id}', [UtangController::class, 'destroyPinjaman'])->name('pinjaman.destroy');
    Route::delete('/pinjaman-delete-kategori/{id}', [UtangController::class, 'destroyPinjamanKategori'])->name('pinjaman.destroyKategori');

    // Pembelian
    Route::get('/deletePembelian/{nomor_nota}', [PembelianController::class, 'delete'])->name('pembelian.delete');
    Route::delete('/destroyPembelian/{nomor_nota}', [PembelianController::class, 'destroy'])->name('pembelian.destroy');

    Route::get('/editPembelian/{nomor_nota}', [PembelianController::class, 'edit'])->name('pembelian.edit');
    Route::put('/editPembelian{nomor_nota}', [PembelianController::class, 'update'])->name('pembelian.update');

    Route::post('/editPelanggan/{id}', [master_kas_controller::class, 'updatePelanggan'])->name('editPelanggan.update');
    Route::delete('/deletePelanggan/{id}', [kas_bankController::class, 'destroyPelanggan'])->name('deletePelanggan.destroy');


    Route::get('/editPenjualan/{nomor_nota}', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/editPenjualan{nomor_nota}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::get('/deletePenjualan/{nomor_nota}', [PenjualanController::class, 'delete'])->name('penjualan.delete');
    Route::delete('/destroyPenjualan/{nomor_nota}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    // User

    Route::get('/multiuser.php', [AuthController::class, 'index'])->name('auth.index');
    Route::get('/statusUser/{id}', [AuthController::class, 'statusUpdate']);

    Route::post('/userStore.php', [AuthController::class, 'addUserstore'])->name('addUser.store');
    Route::get('/user-edit/{idUser}', [AuthController::class, 'edit'])->name('user.edit');
    Route::delete('/deleteUser/{id}', [AuthController::class, 'destroy'])->name('deleteUser.destroy');
});
