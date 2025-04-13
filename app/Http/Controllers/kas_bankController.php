<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\kas_bank;
use App\Models\akun_kas;
use App\Models\categories;
use App\Models\CategorieSupplierModel;
use App\Models\pelanggan;
use App\Models\subcategories;
use App\Models\TransferLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class kas_bankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        $subcategori = $request->kategori;
        if ($subcategori == '2') {
            $valueJumlah = $valueJumlah * (-1);
        }
        // dd($request->kategori);
        $kas_bank = kas_bank::create([
            'nama_akun' => $request->nama_akun,
            'nama_user' => $request->nama_user,
            'tanggal_log' => $request->tanggal_log,
            'jumlah' => $valueJumlah,
            'tanggal_bukti' => $request->tanggal_bukti,
            'subcategories_id' => $request->kategori,
            'kategori' => $request->subcategories_id,
            'keterangan' => $request->keterangan,
            'from' => "Kas",

        ]);
        $kas_bank->save();
        return redirect()->back();
    }

    // public function storeExpense(Request $request)
    // {
    //     $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
    //     $kas_bank = kas_bank::create([
    //         'nama_akun' => $request->nama_akun,
    //         'nama_user' => $request->nama_user,
    //         'tanggal_log' => $request->tanggal_log,
    //         'jumlah' => -$valueJumlah,
    //         'tanggal_bukti' => $request->tanggal_bukti,
    //         'subcategories_id' => $request->kategori,
    //         'kategori' => $request->subcategories_id,
    //         'keterangan' => $request->keterangan,
    //         'from' => "Kas",

    //     ]);
    //     $kas_bank->save();
    //     return redirect()->back();
    // }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $kas = akun_kas::all();
        $showdetail = kas_bank::where('id',$id)->firstOrFail();
        $linkCategori = CategorieSupplierModel::all();

        return view('bukuKas.detail',compact('showdetail','kas','linkCategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kas = akun_kas::all();
        $showdetail = kas_bank::where('id',$id)->firstOrFail();
        $kategori = subcategories::all();
        // dd($kategori);
        // $kategoriList = ['Pembayaran', 'Pinjaman', 'Pemasukan', 'Pengeluaran'];
        $linkCategori = CategorieSupplierModel::all();

        return view('bukuKas.edit',compact('showdetail','kas','linkCategori','kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'tanggal_bukti' => 'required|date',
            'tanggal_log' => 'required|date',
            'nama_akun' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'jumlah' => 'required',
        ]);
    
        // Ambil nilai jumlah tanpa karakter non-digit dan konversi ke integer
        $jumlah = preg_replace('/[^\d-]/', '', $request->input('jumlah'));
        $jumlah = intval($jumlah);
    
        // Validasi jumlah berdasarkan subcategories_id
        if (($request->subcategories_id == 1 && $jumlah < 0) || ($request->subcategories_id == 2 && $jumlah > 0)) {
            return redirect()->back()->withErrors(['jumlah' => 'Nominal harus positif untuk kategori pemasukan dan negatif untuk kategori pengeluaran.'])->withInput();
        }
        // Cari data KasBank berdasarkan $id
        $kasBank = kas_bank::findOrFail($id);
        $oldSubcategories_id = $kasBank->subcategories_id;
        $namaAkun = $kasBank->nama_akun;
    
        // Update data berdasarkan input dari request
        $kasBank->tanggal_bukti = $request->tanggal_bukti;
        $kasBank->tanggal_log = $request->tanggal_log;
        $kasBank->nama_akun = $request->nama_akun;
        $kasBank->keterangan = $request->keterangan;
        $kasBank->jumlah = $jumlah;
    
        // Update kategori dan subcategories_id hanya jika nilainya berubah
        if ($kasBank->kategori !== $request->kategori) {
            $kasBank->subcategories_id = $request->subcategories_id;
            $kasBank->kategori = $request->kategori;
        }
    
        // Simpan perubahan
        $kasBank->save();
    
        // Redirect kembali dengan pesan sukses
        return redirect('/akun_kas/'.$namaAkun)->with('success', 'Data berhasil diperbarui');
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(kas_bank $id)
    {
        $id->delete();
        return redirect()->back()->with('success', 'Kas deleted successfully');
    }

    // Javascript

    public function getKas(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
    
        $penjualanQuery = DB::table('penjualan');
    
        $totalPenjualan = $penjualanQuery->sum('total');
    
        $kasQuery = DB::table('kas_bank');
    
        if ($startDate) {
            $kasQuery->whereDate('tanggal_bukti', '>=', $startDate);
        }
    
        if ($endDate) {
            $kasQuery->whereDate('tanggal_bukti', '<=', $endDate);
        }
    
        if ($search) {
            $kasQuery->where('nama_akun', 'like', '%' . $search . '%');
        }
    
        $hitungKas = $kasQuery->count();
        $pemasukan = $kasQuery->where('subcategories_id', '1')->sum('jumlah');
        $pengeluaran = $kasQuery->where('subcategories_id', '2')->sum('jumlah');
        $jumlahKas = $pemasukan - $pengeluaran;
    
        return response()->json([
            'jumlahKas' => $jumlahKas,
            'hitungKas' => $hitungKas,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'totalPenjualan' => $totalPenjualan,
        ]);
    }

    public function kasData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');
    
        // Query for pemasukan with optional filters
        $pemasukanQuery = DB::table('kas_bank')
            ->where('subcategories_id', 1)
            ->where('nama_akun', $search);
    
        // Apply filters to pemasukan query
        if ($startDate) {
            $pemasukanQuery->whereDate('tanggal_bukti', '>=', $startDate);
        }
    
        if ($endDate) {
            $pemasukanQuery->whereDate('tanggal_bukti', '<=', $endDate);
        }
    
        if ($search) {
            $pemasukanQuery->where('nama_akun', 'like', '%' . $search . '%');
        }
    
        $pemasukan = $pemasukanQuery->pluck('jumlah', 'kategori');
    
        // Query for pengeluaran with optional filters
        $pengeluaranQuery = DB::table('kas_bank')
            ->where('subcategories_id', 2)
            ->where('nama_akun', $search);
    
        // Apply filters to pengeluaran query
        if ($startDate) {
            $pengeluaranQuery->whereDate('tanggal_bukti', '>=', $startDate);
        }
    
        if ($endDate) {
            $pengeluaranQuery->whereDate('tanggal_bukti', '<=', $endDate);
        }
    
        if ($search) {
            $pengeluaranQuery->where('nama_akun', 'like', '%' . $search . '%');
        }
    
        $pengeluaran = $pengeluaranQuery->pluck('jumlah', 'kategori');
    
        return response()->json([
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function PageViewData(Request $request)
    {
       // Get filter parameters
       $startDate = $request->input('start_date');
       $endDate = $request->input('end_date');
       $search = $request->input('search');

       // Query with optional filters
       $query = DB::table('kas_bank')
           ->select(DB::raw('DATE(tanggal_bukti) as date'), DB::raw('SUM(jumlah) as value'))
           ->groupBy(DB::raw('DATE(tanggal_bukti)'))
           ->orderBy('date');

       if ($startDate) {
           $query->whereDate('tanggal_bukti', '>=', $startDate);
       }

       if ($endDate) {
           $query->whereDate('tanggal_bukti', '<=', $endDate);
       }

       if ($search) {
           $query->where('nama_akun', 'like', '%' . $search . '%');
       }

       $kasData = $query->get();

       return response()->json($kasData);
    }

    public function destroyPelanggan($id)
    {
        $pelanggan = pelanggan::where('id',$id);

        $pelanggan->delete();

        return redirect()->back();
    }

    public function transfer(Request $request)
    {
        // Validasi permintaan masuk
        $request->validate([
            'from_account' => 'required',
            'to_account' => 'required',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);
    
        // Ambil akun dan detail transfer
        $fromAccount = akun_kas::where('nama_akun',$request->from_account)->first();
        $toAccount = akun_kas::where('nama_akun',$request->to_account)->first();
        // dd($toAccount->id);
        $amount = $request->amount;
        $description = $request->description;
        // dd($amount);

        // Debugging: Dump dan die untuk memeriksa $fromAccount
        // dd($fromAccount->saldo);
    
        // Lakukan logika transfer
        // Diasumsikan $fromAccount dan $toAccount memiliki field saldo yang sesuai
        $fromBank = kas_bank::create([
                'tanggal_bukti' => Carbon::now(),
                'nama_akun' => $fromAccount->nama_akun,
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'subcategories_id' => '2',
                'kategori' => 'Transfer',
                'from' => 'Transfer',
                'jumlah' => $amount * (-1),
                'keterangan' => $request->description,
                'nama_user' => Auth::user()->username,
                'tanggal_log' => Carbon::now(),
            ]);
        $toBank = kas_bank::create([
                'tanggal_bukti' => Carbon::now(),
                'nama_akun' => $toAccount->nama_akun,
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'subcategories_id' => '1',
                'kategori' => 'Transfer',
                'from' => 'Transfer',
                'jumlah' => $amount,
                'keterangan' => $request->description,
                'nama_user' => Auth::user()->username,
                'tanggal_log' => Carbon::now(),
            ]);

        if ($fromBank && $toBank) {
            // // Kurangi saldo dari akun sumber
            // $fromAccount->jumlah -= $amount;
            // $fromAccount->save();
    
            // // Tambahkan saldo ke akun tujuan
            // $toAccount->jumlah += $amount;
            // $toAccount->save();
    
            // Buat catatan transfer jika diperlukan
            // TransferLog::create([
            //     'from_account' => $fromAccount->id,
            //     'to_account' => $toAccount->id,
            //     'amount' => $amount,
            //     'description' => $description,
            // ]);
    
            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Transfer berhasil.');
        } else {
            // Redirect dengan pesan kesalahan jika saldo tidak mencukupi
            return redirect()->back()->with('error', 'Saldo tidak mencukupi untuk melakukan transfer.');
        }
    }

    public function editTransfer($id)
    {
        // Cari transfer berdasarkan ID
        $transfer = kas_bank::with('fromAccount','toAccount')->find($id); // This returns the model instance
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $kategori = subcategories::all();
        if (!$transfer) {
            return redirect()->back()->with('error', 'Transfer tidak ditemukan.');
        }
    
        // Tampilkan formulir edit dengan data transfer yang ada
        // return view('transfer.edit', compact('transfer','kas','linkCategori','kategori'));
        return response()->json(
            [
                'id' => $transfer->id,
                'from_account' => $transfer->nama_akun,
                'to_account' => $transfer->toAccount->nama_akun, 
                'amount' => $transfer->jumlah,
                'description' => $transfer->keterangan
            ]
            );
    }
    

    public function updateTransfer(Request $request, $id)
    {
        // Validasi permintaan masuk
        $request->validate([
            'from_account' => 'required',
            'to_account' => 'required',
            'amount' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Ambil transfer berdasarkan ID
        $transfer = kas_bank::find($id);
        if (!$transfer) {
            return redirect()->back()->with('error', 'Transfer tidak ditemukan.');
        }

        // Ambil data dari request
        $fromAccount = akun_kas::where('nama_akun', $request->from_account)->first();
        $toAccount = akun_kas::where('nama_akun', $request->to_account)->first();

        if (!$fromAccount || !$toAccount) {
            return redirect()->back()->with('error', 'Akun yang ditentukan tidak ditemukan.');
        }

        $amount = $request->amount;
        $description = $request->keterangan;

        if ($amount > 0) {
            $oldTanggalLog = $transfer->tanggal_log;

            // Update entri transfer yang sesuai berdasarkan tanggal_log
            $transfer->update([
                'tanggal_bukti' => Carbon::now(),
                'nama_akun' => $fromAccount->nama_akun,
                'subcategories_id' => 2, // Misalkan ini subkategori untuk transfer keluar
                'from_account_id' => $fromAccount->id,
                'to_account_id' => $toAccount->id,
                'kategori' => 'Transfer',
                'from' => 'Transfer',
                'jumlah' => $amount,
                'keterangan' => $description,
                'nama_user' => Auth::user()->username,
                'tanggal_log' => Carbon::now(),
            ]);

            // Update entri transfer tujuan
            $transfer_to_account =  kas_bank::where('tanggal_log', $oldTanggalLog)
                ->where('to_account_id', $toAccount->id)
                ->update([
                    'tanggal_bukti' => Carbon::now(),
                    'nama_akun' => $toAccount->nama_akun,
                    'subcategories_id' => 1, // Misalkan ini subkategori untuk transfer masuk
                    'from_account_id' => $fromAccount->id,
                    'to_account_id' => $toAccount->id,
                    'kategori' => 'Transfer',
                    'from' => 'Transfer',
                    'jumlah' => $amount,
                    'keterangan' => $description,
                    'nama_user' => Auth::user()->username,
                    'tanggal_log' => Carbon::now(),
                ]);
                dd($transfer_to_account);

        }

        // Update saldo akun jika diperlukan (tambahkan logika saldo jika ada perubahan saldo)

        return redirect()->back()->with('success', 'Transfer berhasil diubah.');
    }

    public function destroyTransfer($id)
    {
        // Cari transfer berdasarkan ID
        $fromAccount = kas_bank::find($id);
        $oldNamaAkun = $fromAccount->nama_akun;
        $oldTanggalLog = $fromAccount->tanggal_log;
        // dd($fromAccount);
        if (!$fromAccount) {
            return redirect()->back()->with('error', 'Transfer tidak ditemukan.');
        }
        $toAccount = kas_bank::where('tanggal_log',$oldTanggalLog);
        // Hapus fromAccount
        $fromAccount->delete();
        $toAccount->delete();

        // Jika perlu, kembalikan saldo akun yang terlibat (tambahkan logika saldo)
        return redirect()->route('akun_kas.show', ['nama_akun' => $oldNamaAkun])->with('success', 'Transfer berhasil dihapus.');
    }


    public function searchKas(Request $request)
    {
        $search = $request->input('q');

        $result = kas_bank::where('nama_akun', 'LIKE', "%{$search}%")
                            ->orWhere('jumlah', 'LIKE', "%{$search}%")
                            ->orWhere('id', 'LIKE', "%{$search}%")
                            ->orWhere('tanggal_transaksi', 'LIKE', "%{$search}%")
                            ->get(['nama_akun', 'jumlah','id']);

        return response()->json($result);
    }
    
}
