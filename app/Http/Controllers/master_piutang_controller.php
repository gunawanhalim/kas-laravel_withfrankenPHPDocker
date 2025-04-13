<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\piutang;
use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use App\Models\pelanggan;
use App\Models\subcategories;

class master_piutang_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function debit()
    // {
    //     $debit = piutang::with('kas_bank')->paginate(25);

    //     $kas = akun_kas::paginate(25);

    //     $kas_bank = kas_bank::paginate(50);

    //     // Prepare balances array
    //     $balances = $kas->mapWithKeys(function ($nama_akun) use ($kas_bank) {
    //         $relatedTransactions = $kas_bank->where('nama_akun', $nama_akun->nama_akun);

    //         $totalPemasukan = $relatedTransactions->where('subcategories_id', '1')->sum('jumlah');
    //         $totalPengeluaran = $relatedTransactions->where('subcategories_id', '2')->sum('jumlah');
    //         $saldo = $totalPemasukan - $totalPengeluaran;

    //         return [
    //             $nama_akun->nama_akun => [
    //                 'total_pemasukan' => $totalPemasukan,
    //                 'total_pengeluaran' => $totalPengeluaran,
    //                 'saldo' => $saldo,
    //             ],
    //         ];
    //     });

    //     return view('utang_piutang.debit',compact('kas_bank','kas','balances'));
    // }
    public function index(Request $request)
    {
        $kas = akun_kas::paginate(25);
        $kas_bank = kas_bank::paginate(50);
        $linkCategori = CategorieSupplierModel::all();
        // Query dasar untuk piutang dengan relasi ke kas_bank
            // Query dasar untuk piutang dengan relasi ke kas_bank
            $query = piutang::with('kas_bank')
            ->selectRaw('
                nomor_bukti,
                SUM(CASE WHEN kategori = "Piutang" THEN -jumlah ELSE jumlah END) as total_jumlah,
                MAX(tanggal_bukti) as tanggal_bukti,
                MAX(nomor_nota) as nomor_nota,
                MAX(kategori) as kategori,
                MAX(nama_akun) as nama_akun,
                MAX(nama_pelanggan) as nama_pelanggan,
                MAX(jatuh_tempo) as jatuh_tempo

            ')
            ->groupBy('nomor_bukti');
        
        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $category = $request->category;
            $query->where(function ($q) use ($category, $request) {
                $q->where($category, 'like', '%' . $request->search . '%');
            });
        }
    
        // Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('tanggal_bukti', [$startDate, $endDate]);
        }
    
        // Filter berdasarkan pencarian umum
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', '%' . $search . '%')
                    ->orWhere('nomor_nota', 'like', '%' . $search . '%')
                    ->orWhere('jumlah', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%')
                    ->orWhere('nama_akun', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $search . '%');
            });
        }
    
        // Urutan berdasarkan kolom dan arah
        if ($request->has('column') && $request->has('order')) {
            $column = $request->input('column');
            $order = $request->input('order');
            $query->orderBy($column, $order);
        } else {
            $query->orderBy('nomor_bukti', 'asc'); // Default sorting
        }
    
        // Ambil data dengan paginasi
        $piutang = $query->paginate(25);
    
        return view('utang_piutang.index', compact('kas', 'kas_bank', 'piutang','linkCategori'));
    }

    public function debit()
    {
        
        $kas = akun_kas::paginate(25);
        $kas_bank = kas_bank::paginate(50);
        $debit = piutang::with('kas_bank')->paginate(25);

        $balances = $kas->mapWithKeys(function ($nama_akun) use ($debit) {
            $nama_akun_name = $nama_akun->nama_akun;
            $relatedTransactions = $debit->where('nama_akun', $nama_akun_name);
        
            $totalPemasukan = $relatedTransactions->where('kategori', '1')->sum('jumlah');
            $totalPengeluaran = $relatedTransactions->where('kategori', '2')->sum('jumlah');
            
            $latestPemasukanTransaction = $relatedTransactions->where('kategori', '2')->sortByDesc('tanggal_bukti')->first();
            $tanggal_terakhir = $latestPemasukanTransaction ? $latestPemasukanTransaction->tanggal_bukti : 'Kosong';
            
            $saldo = $totalPemasukan - $totalPengeluaran;
        
            $id_akun_kas = $nama_akun->id;
        
            return [
                $nama_akun_name => [
                    'nama_akun' => $nama_akun_name,
                    'id_akun_kas' => $id_akun_kas,
                    'total_pemasukan' => $totalPemasukan,
                    'total_pengeluaran' => $totalPengeluaran,
                    'tanggal_bukti' => $tanggal_terakhir,
                    'saldo' => $saldo,
                ],
            ];
        });
        $linkCategori = CategorieSupplierModel::all();

        return view('utang_piutang.debit',compact('kas_bank','kas','balances','linkCategori'));
    }

    public function showDebit(Request $request, $nama_akun)
    {   
        $query = piutang::query();
    
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', '%' . $search . '%')
                    ->orWhere('nomor_nota', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $search . '%')
                    ->orWhere('jumlah', 'like', '%' . $search . '%')
                    ->orWhere('nama_user', 'like', '%' . $search . '%');
            });
        }
    
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('tanggal_bukti', '>=', $startDate);
        }
    
        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('tanggal_bukti', '<=', $endDate);
        }
    
        $debit = $query->where('nama_akun', $nama_akun)
                        ->where('kategori',"2")
                        ->with('kas_bank')
                        ->paginate(25);
    
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        return view('utang_piutang.showDebit', compact('kas', 'debit', 'nama_akun','linkCategori'));
    }
    

    public function detailDebit($nomorBukti)
    {
        // Mengambil data transaksi berdasarkan nomor bukti
        $transaksi = piutang::where('nomor_bukti', $nomorBukti)->first();

        // Jika transaksi ditemukan, kirim data dalam format yang sesuai
        if ($transaksi) {
            // Contoh: Kirim data dalam format JSON
            return response()->json($transaksi);
        } else {
            // Jika transaksi tidak ditemukan, kirim respons kosong atau pesan kesalahan
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }
    }

    public function editDebit($nomorBukti)
    {
        $debit = piutang::where('nomor_bukti', $nomorBukti)->first();
        $nama_akun = $debit->nama_akun;
            // Ambil keterangan dari kas_bank berdasarkan tanggal_bukti
        $keterangan = kas_bank::where('tanggal_log', $debit->tanggal_log)->value('keterangan');
        $kategori = subcategories::where('nama_akun', $nama_akun)
                                    ->where('kategori_id', 2)
                                    ->get();
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        // dd($kategori);
        return view('utang_piutang.editDebit',compact('kas','debit','nomorBukti','kategori','keterangan','linkCategori'));
    }
    public function updateDebit(Request $request, $nomorBukti)
    {
        $request->validate([
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            'kategori' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required|date',
        ]);
    
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
    
        $piutangData = $request->except(['_token', '_method']);
        $piutangData['jumlah'] = $valueJumlah;
    
        // Update data piutang
        piutang::where('nomor_bukti', $nomorBukti)->update(
            [
                'nama_akun' => $request->nama_akun,
                'tanggal_bukti' => $request->tanggal_bukti,
                'nomor_nota' => $request->nomor_nota,
                'jumlah' => $valueJumlah,
                'kategori' => $request->kategori,
                'nama_user' => $request->nama_user,
                'nama_pelanggan' => $request->nama_pelanggan,
                'tanggal_log' => Carbon::now(),
                ]
                );
        kas_bank::where('tanggal_bukti', $request->tanggal_bukti)
            ->update([
                'tanggal_bukti' => $request->tanggal_bukti,
                'nama_akun' => $request->nama_akun,
                'kategori' => $request->subcategories_id,
                'subcategories_id' => $request->kategori,
                'jumlah' => $valueJumlah,
                'keterangan' => $request->keterangan,
                'nama_user' => $request->nama_user,
                'tanggal_log' => Carbon::now(),
            ]);
    
        return redirect()->route('debit.show', ['nama_akun' => $request->nama_akun])
            ->with('success', 'Data berhasil diperbarui');
    }

    public function createDebit(string $nama_akun)
    {   
        $debit = piutang::all();
        $kategori = subcategories::where('nama_akun', $nama_akun)
                                    ->where('kategori_id', 2)
                                    ->get();
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        // dd($kategori);
        return view('utang_piutang.createDebit',compact('kas','debit','nama_akun','kategori','linkCategori'));
    }

    public function generateNomorBukti()
    {
        // Ambil nomor bukti terakhir dari database
        $lastNomorBukti = piutang::orderBy('nomor_bukti', 'desc')->first();
        
        if ($lastNomorBukti) {
            // Ambil bagian numerik dari nomor bukti terakhir
            $lastNumber = (int) substr($lastNomorBukti->nomor_bukti, -6);
        } else {
            $lastNumber = 0;
        }

        // Tambahkan 1 ke nomor terakhir
        $newNumber = $lastNumber + 1;

        // Buat nomor bukti baru dengan format 'NB-XXXX'
        $newNomorBukti = 'NB.' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return response()->json(['nomor_bukti' => $newNomorBukti]);
    }
    
    public function debitStore(Request $request)
    {

        $request->validate([
            'nomor_bukti' => 'required|unique:piutang',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            'kategori' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required',
        ]);
        $nama_akun = $request->nama_akun;
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        $piutangData = $request->all();
        $piutangData['jumlah'] = $valueJumlah;
        $piutangData['kategori'] = 2;
        $piutangData['tanggal_log'] = Carbon::now();
        // Buat data piutang
        $piutang = piutang::create($piutangData);

        // Buat data kas_bank
        kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '2',
            'jumlah' => $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        return redirect()->route('debit.show', ['nama_akun' => $nama_akun])->with('success', 'Data telah disimpan.');    
    }

    public function destroyDebit($nomorBukti)
    {
        $piutang = piutang::where('nomor_bukti', $nomorBukti)->firstOrFail();
        $nama_akun = $piutang->nama_akun;
        $kategori = $piutang->kategori;
        $tanggal_log = $piutang->tanggal_log;
    
        // Mengambil data 'kas_bank' dengan tanggal_log terdekat dengan tanggal_log dari 'piutang'
        $kas_bank = kas_bank::where('tanggal_log', '>=', $tanggal_log)
        ->where('nama_akun',$nama_akun)
        ->where('subcategories_id',$kategori)
        ->first(); 
    
        if ($kas_bank) {
            $kas_bank->delete();
        }
    
        $piutang->delete();
    
        return redirect()->route('piutang.index', ['nama_akun' => $nama_akun])->with('success', 'Data Berhasil di hapus');
    }

    public function credit()
    {
        
        $kas = akun_kas::paginate(25);
        $kas_bank = kas_bank::paginate(50);
        $credit = piutang::with('kas_bank')->paginate(25);

        $balances = $kas->mapWithKeys(function ($nama_akun) use ($credit) {
            $nama_akun_name = $nama_akun->nama_akun;
            $relatedTransactions = $credit->where('nama_akun', $nama_akun_name);
        
            $totalPemasukan = $relatedTransactions->where('kategori', '1')->sum('jumlah');
            $totalPengeluaran = $relatedTransactions->where('kategori', '2')->sum('jumlah');
            
            $latestPemasukanTransaction = $relatedTransactions->where('kategori', '1')->sortByDesc('tanggal_bukti')->first();
            $tanggal_terakhir = $latestPemasukanTransaction ? $latestPemasukanTransaction->tanggal_bukti : 'Kosong';
        
            $saldo = $totalPemasukan - $totalPengeluaran;
        
            $id_akun_kas = $nama_akun->id;
        
            return [
                $nama_akun_name => [
                    'nama_akun' => $nama_akun_name,
                    'id_akun_kas' => $id_akun_kas,
                    'total_pemasukan' => $totalPemasukan,
                    'total_pengeluaran' => $totalPengeluaran,
                    'tanggal_bukti' => $tanggal_terakhir,
                    'saldo' => $saldo,
                ],
            ];
        });

        return view('utang_piutang.credit',compact('credit','kas','kas_bank','balances'));
    }

    public function showCredit(Request $request, $nama_akun)
    {   
        $query = piutang::query();
    
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', '%' . $search . '%')
                    ->orWhere('nomor_nota', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $search . '%')
                    ->orWhere('jumlah', 'like', '%' . $search . '%')
                    ->orWhere('nama_user', 'like', '%' . $search . '%');
            });
        }
    
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $query->where('tanggal_bukti', '>=', $startDate);
        }
    
        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->where('tanggal_bukti', '<=', $endDate);
        }
    
        $credit = $query->where('nama_akun', $nama_akun)
                        ->where('kategori',"1")
                        ->with('kas_bank')
                        ->paginate(25);
    
        $kas = akun_kas::paginate(25);
        return view('utang_piutang.showCredit', compact('kas', 'credit', 'nama_akun'));
    }

    public function createCredit(string $nama_akun)
    {   
        $credit = piutang::all();
        $kategori = subcategories::where('nama_akun', $nama_akun)
                                    ->where('kategori_id', 1)
                                    ->get();
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        // dd($kategori);
        return view('utang_piutang.createCredit',compact('kas','credit','nama_akun','kategori','linkCategori'));
    }

    public function creditStore(Request $request)
    {
            $request->validate([
                'nomor_bukti' => 'required|unique:piutang',
                'nama_akun' => 'required|string',
                'nama_user' => 'required|string',
                'nomor_nota' => 'required',
                'kategori' => 'required',
                'jumlah' => 'required',
                'nama_pelanggan' => 'required',
                'tanggal_bukti' => 'required',
            ]);
            $nama_akun = $request->nama_akun;
            $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
            $piutangData = $request->all();
            $piutangData['jumlah'] = $valueJumlah;
            $piutangData['kategori'] = 1;
            $piutangData['tanggal_log'] = Carbon::now();
            // Buat data piutang
            $piutang = piutang::create($piutangData);
    
            // Buat data kas_bank
            kas_bank::create([
                'tanggal_bukti' => $request->tanggal_bukti,
                'nama_akun' => $nama_akun,
                'subcategories_id' => '1',
                'jumlah' => $valueJumlah,
                'kategori' => $request->kategori,
                'keterangan' => $request->keterangan,
                'nama_user' => $request->nama_user,
                'tanggal_log' => Carbon::now(),
                // // Sisipkan id piutang sebagai foreign key
                // 'piutang_id' => $piutang->id,
            ]);
            return redirect()->route('credit.show', ['nama_akun' => $nama_akun])->with('success', 'Data telah disimpan.');    
    }
    public function editCredit($nomorBukti)
    {
        $credit = piutang::where('nomor_bukti', $nomorBukti)->first();
        $nama_akun = $credit->nama_akun;
            // Ambil keterangan dari kas_bank berdasarkan tanggal_bukti
        $keterangan = kas_bank::where('tanggal_log', $credit->tanggal_log)->value('keterangan');
        $kategori = subcategories::where('nama_akun', $nama_akun)
                                    ->where('kategori_id', 1)
                                    ->get();
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        // dd($kategori);
        return view('utang_piutang.editCredit',compact('kas','credit','nomorBukti','kategori','keterangan','linkCategori'));
    }

    public function updateCredit(Request $request, $nomorBukti)
    {
        $request->validate([
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            'kategori' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required|date',
        ]);
    
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
    
        $piutangData = $request->except(['_token', '_method']);
        $piutangData['jumlah'] = $valueJumlah;
    
        // Update data piutang
        piutang::where('nomor_bukti', $nomorBukti)->update(
            [
                'nama_akun' => $request->nama_akun,
                'tanggal_bukti' => $request->tanggal_bukti,
                'nomor_nota' => $request->nomor_nota,
                'jumlah' => $valueJumlah,
                'kategori' => $request->kategori,
                'nama_user' => $request->nama_user,
                'nama_pelanggan' => $request->nama_pelanggan,
                'tanggal_log' => Carbon::now(),
                ]
                );
        kas_bank::where('tanggal_bukti', $request->tanggal_bukti)
            ->update([
                'tanggal_bukti' => $request->tanggal_bukti,
                'nama_akun' => $request->nama_akun,
                'kategori' => $request->subcategories_id,
                'subcategories_id' => $request->kategori,
                'jumlah' => $valueJumlah,
                'keterangan' => $request->keterangan,
                'nama_user' => $request->nama_user,
                'tanggal_log' => Carbon::now(),
            ]);
    
        return redirect()->route('credit.show', ['nama_akun' => $request->nama_akun])
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroyCredit($nomorBukti)
    {
        $piutang = piutang::where('nomor_bukti', $nomorBukti)->firstOrFail();
        $nama_akun = $piutang->nama_akun;
        $kategori = $piutang->kategori;
        $tanggal_log = $piutang->tanggal_log;
    
        // Mengambil data 'kas_bank' dengan tanggal_log terdekat dengan tanggal_log dari 'piutang'
        $kas_bank = kas_bank::where('tanggal_log', '>=', $tanggal_log)
        ->where('nama_akun',$nama_akun)
        ->where('subcategories_id',$kategori)
        ->first(); 
    
        if ($kas_bank) {
            $kas_bank->delete();
        }
    
        $piutang->delete();
    
        return redirect()->route('piutang.index')->with('success', 'Data Berhasil di hapus');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kas = akun_kas::all();
        $piutang = piutang::all();
        $kategori = subcategories::all();
        return view('utang_piutang.create',compact('kas','piutang','kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required|unique:piutang',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            'kategori' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required',
        ],[
            'kategori.required' => 'Kategori Wajib di isi',
            'nomor_bukti.required' => 'Nomor Bukti Wajib di isi',
            'tanggal_bukti.required' => 'Tanggal Bukti Wajib di isi',
        ]
        );

        // dd($request->kategori);
        $nama_akun = $request->nama_akun;
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        $piutangData = $request->all();
        $piutangData['jumlah'] = $valueJumlah;
        $piutangData['kategori'] = $request->subcategories_id;
        $piutangData['tanggal_log'] = Carbon::now();
        // Buat data piutang
        $piutang = piutang::create($piutangData);

        // Buat data kas_bank
        kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => $request->subcategories_id,
            'jumlah' => $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        return redirect()->route('piutang.index')->with('success', 'Data telah disimpan.');    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nomorBukti)
    {
        $kas = akun_kas::all();
        $piutang = piutang::where('nomor_bukti',$nomorBukti);
        $keterangan = kas_bank::where('tanggal_log', $piutang->tanggal_log)->value('keterangan');
        $kategori = subcategories::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('utang_piutang.edit',compact('kas','piutang','kategori','keterangan','linkCategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nomorBukti)
    {
            $request->validate([
                'nama_akun' => 'required|string',
                'nama_user' => 'required|string',
                'nomor_nota' => 'required',
                'kategori' => 'required',
                'jumlah' => 'required',
                'nama_pelanggan' => 'required',
                'tanggal_bukti' => 'required|date',
            ]);
        
            $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        
            $piutangData = $request->except(['_token', '_method']);
            $piutangData['jumlah'] = $valueJumlah;
        
            // Update data piutang
            piutang::where('nomor_bukti', $nomorBukti)->update(
                [
                    'nama_akun' => $request->nama_akun,
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'nomor_nota' => $request->nomor_nota,
                    'jumlah' => $valueJumlah,
                    'kategori' => $request->kategori,
                    'nama_user' => $request->nama_user,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'tanggal_log' => Carbon::now(),
                    ]
                    );
            kas_bank::where('tanggal_bukti', $request->tanggal_bukti)
                ->update([
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'nama_akun' => $request->nama_akun,
                    'kategori' => $request->subcategories_id,
                    'subcategories_id' => $request->kategori,
                    'jumlah' => $valueJumlah,
                    'keterangan' => $request->keterangan,
                    'nama_user' => $request->nama_user,
                    'tanggal_log' => Carbon::now(),
                ]);
        
            return redirect()->route('piutang.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function pembayaranDetail($nomorBukti)
    {
        // Mengambil data transaksi berdasarkan nomor bukti
        $transaksi = piutang::where('nomor_bukti', $nomorBukti)->first();

        // Jika transaksi ditemukan, kirim data dalam format yang sesuai
        if ($transaksi) {
            // Contoh: Kirim data dalam format JSON
            return response()->json($transaksi);
        } else {
            // Jika transaksi tidak ditemukan, kirim respons kosong atau pesan kesalahan
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }
    }

    public function detailRincianPembayaran($id)
    {
        // Mengambil data transaksi berdasarkan nomor bukti
        $transaksi = piutang::where('id', $id)->first();

        // Jika transaksi ditemukan, kirim data dalam format yang sesuai
        if ($transaksi) {
            // Contoh: Kirim data dalam format JSON
            return response()->json($transaksi);
        } else {
            // Jika transaksi tidak ditemukan, kirim respons kosong atau pesan kesalahan
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }
    }

    public function detailPembayaran($nomor_bukti)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        // Mengambil semua entri dengan nomor_bukti yang sama
        // $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->with('kategoriSupplier')->paginate(25);
        $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->paginate(25);
    
        return view('pembayaran.detail', compact('pembayaran', 'kas','nomor_bukti','linkCategori'));
    }

    public function createPembayaran($nomor_bukti)
    {

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $pelanggan = pelanggan::all();
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('pembayaran.create', compact('pembayaran','kas','nomor_bukti','kategori','linkCategori','pelanggan'));
    }

    public function createPembayaranBayar($nomor_bukti)
    {

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $pelanggan = pelanggan::all();
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('pembayaran.createBayar', compact('pembayaran','kas','kategori','linkCategori','pelanggan'));
    }

    public function createPembayaranTambah($nomor_bukti)
    {

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $pelanggan = pelanggan::all();
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('pembayaran.createTambah', compact('pembayaran','kas','kategori','linkCategori','pelanggan'));
    }
    
    public function storePembayaran(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required',
        ],[
            // 'kategori.required' => 'Kategori Wajib di isi',
            'nomor_bukti.required' => 'Nomor Bukti Wajib di isi',
            'tanggal_bukti.required' => 'Tanggal Bukti Wajib di isi',
        ]
        );
        
        // dd($request->all());
        $nama_akun = $request->nama_akun;
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        $piutangData = $request->all();
        $piutangData['jumlah'] = (-1) * $valueJumlah;
        $piutangData['kategori'] = $request->kategori;
        $piutangData['tanggal_log'] = Carbon::now();
        $piutangData['jatuh_tempo'] = $request->jatuh_tempo;

        // Buat data piutang
        $piutang = piutang::create($piutangData);

        // Buat data kas_bank
        $kas_bank = kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '1',
            'jumlah' => $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nama_user' => $request->nama_user,
            'nomor_bukti' => $request->nomor_bukti,
            'from' => "Piutang",
            'tanggal_log' => Carbon::now(),

            'jatuh_tempo' => $request->jatuh_tempo,

            
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank){
            return redirect()->route('piutang.index')->with('success', 'Data telah disimpan.');    
        }
    }
    public function storePembayaranBayar(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required',
        ],[
            // 'kategori.required' => 'Kategori Wajib di isi',
            'nomor_bukti.required' => 'Nomor Bukti Wajib di isi',
            'tanggal_bukti.required' => 'Tanggal Bukti Wajib di isi',
        ]
        );

        // dd($request->kategori);
        $nama_akun = $request->nama_akun;
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        $piutangData = $request->all();
        $piutangData['jumlah'] = (-1) * $valueJumlah;
        $piutangData['kategori'] = $request->kategori;
        $piutangData['tanggal_log'] = Carbon::now();
        $piutangData['jatuh_tempo'] = $request->jatuh_tempo;

        // Buat data piutang
        $piutang = piutang::create($piutangData);

        // Buat data kas_bank
        $kas_bank = kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '1',
            'jumlah' => $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            'from' => "Piutang",
            'nomor_bukti' => $request->nomor_bukti,

            'jatuh_tempo' => $request->jatuh_tempo,

            
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank){
            return redirect('pembayaran-kategoriDetail/' . urlencode($request->nomor_bukti))
            ->with('success', 'Data telah disimpan.');  
        }
    }
    public function storePembayaranTambah(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required',
        ],[
            // 'kategori.required' => 'Kategori Wajib di isi',
            'nomor_bukti.required' => 'Nomor Bukti Wajib di isi',
            'tanggal_bukti.required' => 'Tanggal Bukti Wajib di isi',
        ]
        );

        // dd($request->kategori);
        $nama_akun = $request->nama_akun;
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        $piutangData = $request->all();
        $piutangData['jumlah'] = $valueJumlah;
        $piutangData['kategori'] = $request->kategori;
        $piutangData['tanggal_log'] = Carbon::now();
        $piutangData['jatuh_tempo'] = $request->jatuh_tempo;

        // Buat data piutang
        $piutang = piutang::create($piutangData);

        // Buat data kas_bank
        $kas_bank = kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '2',
            'jumlah' => (-1) * $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            'from' => "Piutang",
            'nomor_bukti' => $request->nomor_bukti,
            'jatuh_tempo' => $request->jatuh_tempo,

            
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank){
            return redirect('pembayaran-kategoriDetail/' . urlencode($request->nomor_bukti))
            ->with('success', 'Data telah disimpan.');  
        }
    }

    public function editPembayaran($id)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        $pembayaran = piutang::where('id',$id)->firstOrFail();
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        return view('pembayaran.edit',compact('pembayaran','kas','kategori','linkCategori'));
    }

    public function updatePembayaran(Request $request,$id)
    {
        $request->validate(
            [
                'jumlah' => 'required',

            ],[
                'jumlah.required' => 'Jumlah Wajib di isi'
            ]
        );

        $pembayaran = $request->except(['_token', '_method']);
        $valueJumlah = (int) str_replace(['Rp. ', '.'], '', $request->jumlah);
        $tanggal_log = piutang::where('id',$id)->value('tanggal_log');
    
            // dd($tanggal_log);
            $piutang = piutang::where('id', $id)->update(
                [
                    'nama_akun' => $request->nama_akun,
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'jumlah' => (-1) * $valueJumlah,
                    'kategori' => $request->kategori,
                    'nama_user' => $request->nama_user,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'tanggal_log' => Carbon::now(),
                    ]
                );
            
                $kas_bank = kas_bank::where('tanggal_log', $tanggal_log)->update(
                    [
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'nama_akun' => $request->nama_akun,
                    'jumlah' => $valueJumlah,
                    'subcategories_id' => "1",
                    'kategori' => $request->kategori,
                    'nama_user' => $request->nama_user,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'from' => "Piutang",
                    'nomor_bukti' => $request->nomor_bukti,
                    'tanggal_log' => Carbon::now(),
                    ]
                );
                if($piutang && $kas_bank){
                    return redirect()->route('piutang.index')->with('success', 'Data telah di ubah.');  
                }

    }


    public function destroyPembayaran($id)  
    {
        $pembayaran = piutang::where('id', $id)->firstOrFail();
        $nama_akun = $pembayaran->nama_akun;
        $kategori = $pembayaran->kategori;
        $tanggal_log = $pembayaran->tanggal_log;
    
        // Mengambil data 'kas_bank' dengan tanggal_log terdekat dengan tanggal_log dari 'pembayaran'
        $kas_bank = kas_bank::where('tanggal_log', '>=', $tanggal_log)
        ->where('nama_akun',$nama_akun)
        ->where('kategori',$kategori)
        ->first(); 
    
        if ($kas_bank) {
            $kas_bank->delete();
        }
    
        $pembayaran->delete();
    
        return redirect()->route('piutang.index')->with('success', 'Data Berhasil di hapus');
        // return redirect()->route('piutang.index', ['nama_akun' => $nama_akun])->with('success', 'Data Berhasil di hapus');
    }
    public function destroyPembayaranKategori($id)  
    {
        $pembayaran = piutang::where('id', $id)->firstOrFail();
        $nama_akun = $pembayaran->nama_akun;
        $kategori = $pembayaran->kategori;
        $tanggal_log = $pembayaran->tanggal_log;
    
        // Mengambil data 'kas_bank' dengan tanggal_log terdekat dengan tanggal_log dari 'pembayaran'
        $kas_bank = kas_bank::where('tanggal_log', '>=', $tanggal_log)
        ->where('nama_akun',$nama_akun)
        ->where('kategori',$kategori)
        ->first(); 
    
        if ($kas_bank) {
            $kas_bank->delete();
        }
    
        $pembayaran->delete();
    
        return redirect('pembayaran-kategoriDetail/' . urlencode($pembayaran->nomor_bukti))
        ->with('success', 'Data telah dihapus.'); 
        // return redirect()->route('piutang.index', ['nama_akun' => $nama_akun])->with('success', 'Data Berhasil di hapus');
    }

    
}
