<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use App\Models\UtangModel;
use Carbon\Carbon;

class UtangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kas = akun_kas::paginate(25);
        $kas_bank = kas_bank::paginate(50);
        $linkCategori = CategorieSupplierModel::all();

        
        $query = UtangModel::with('kas_bank')
        ->selectRaw('
            nomor_bukti,
            SUM(jumlah) as total_jumlah,
            MAX(tanggal_bukti) as tanggal_bukti,
            MAX(nomor_nota) as nomor_nota,
            MAX(nama_sales) as nama_sales,
            MAX(kategori) as kategori,
            MAX(nama_akun) as nama_akun,
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
                    ->orWhere('nama_akun', 'like', '%' . $search . '%');
                    // ->orWhere('nama_pelanggan', 'like', '%' . $search . '%');
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
        $utang = $query->paginate(25);
    
        return view('utang.index', compact('kas', 'kas_bank', 'utang','linkCategori'));
    }

    public function utangDetail($nomorBukti)
    {
        // Mengambil data transaksi berdasarkan nomor bukti
        $transaksi = UtangModel::where('nomor_bukti', $nomorBukti)->first();

        // Jika transaksi ditemukan, kirim data dalam format yang sesuai
        if ($transaksi) {
            // Contoh: Kirim data dalam format JSON
            return response()->json($transaksi);
        } else {
            // Jika transaksi tidak ditemukan, kirim respons kosong atau pesan kesalahan
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }
    }

    
    public function detailRincianPinjaman($id)
    {
        // Mengambil data transaksi berdasarkan nomor bukti
        $transaksi = UtangModel::where('id', $id)->first();

        // Jika transaksi ditemukan, kirim data dalam format yang sesuai
        if ($transaksi) {
            // Contoh: Kirim data dalam format JSON
            return response()->json($transaksi);
        } else {
            // Jika transaksi tidak ditemukan, kirim respons kosong atau pesan kesalahan
            return response()->json(['error' => 'Transaksi tidak ditemukan.'], 404);
        }
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
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function detailPerutangan($nomor_bukti)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        // Mengambil semua entri dengan nomor_bukti yang sama
        $pembayaran = UtangModel::where('nomor_bukti', $nomor_bukti)->paginate(25);
    
        return view('pinjaman.detail', compact('pembayaran', 'kas','nomor_bukti','linkCategori'));
    }
    
    public function createPinjaman($nomor_bukti)
    {

        $kas = akun_kas::all();
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        $linkCategori = CategorieSupplierModel::all();

        $pinjaman = UtangModel::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('pinjaman.create', compact('pinjaman','kas','nomor_bukti','kategori','linkCategori'));
    }
    public function createPinjamanBayar($nomor_bukti)
    {

        $kas = akun_kas::all();
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        $linkCategori = CategorieSupplierModel::all();

        $pinjaman = UtangModel::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('pinjaman.create_kategoribayar', compact('pinjaman','kas','nomor_bukti','kategori','linkCategori'));
    }

    public function createPinjamanTambah($nomor_bukti)
    {

        $kas = akun_kas::all();
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        $linkCategori = CategorieSupplierModel::all();

        $pinjaman = UtangModel::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('pinjaman.create_kategoritambah', compact('pinjaman','kas','nomor_bukti','kategori','linkCategori'));
    }

    public function storePinjaman(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            // 'nama_pelanggan' => 'required',
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
        $utangData = $request->all();
        $utangData['jumlah'] = (-1) * $valueJumlah;
        $utangData['kategori'] = $request->kategori;
        $utangData['nama_sales'] = $request->nama_sales;
        $utangData['tanggal_log'] = Carbon::now();
        // Buat data piutang
        $utang = UtangModel::create($utangData);

        // Buat data kas_bank
        $kas_bank = kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nomor_bukti' => $request->nomor_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '2',
            'jumlah' => (-1) * $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_user' => $request->nama_user,
            'nama_sales_utang' => $request->nama_sales,
            'tanggal_log' => Carbon::now(),
            'from' => "Utang",

            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank && $utang){
            return redirect()->route('utang.index')->with('success', 'Data telah disimpan.');    
        }
    }
    public function storePinjamanBayar(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            // 'nama_pelanggan' => 'required',
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
        $utangData = $request->all();
        $utangData['jumlah'] = (-1) * $valueJumlah;
        $utangData['kategori'] = $request->kategori;
        $utangData['nama_sales'] = $request->nama_sales;
        $utangData['tanggal_log'] = Carbon::now();
        // Buat data piutang
        $utang = UtangModel::create($utangData);

        // Buat data kas_bank
        $kas_bank = kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nomor_bukti' => $request->nomor_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '2',
            'jumlah' => (-1) * $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_sales_utang' => $request->nama_sales,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            'from' => "Utang",

            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank && $utang){
            return redirect('pinjaman-kategoriDetail/' . urlencode($request->nomor_bukti))
            ->with('success', 'Data telah disimpan.');
        }
    }
    public function storePinjamanTambah(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            // 'nama_pelanggan' => 'required',
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
        $utangData = $request->all();
        $utangData['jumlah'] = $valueJumlah;
        $utangData['kategori'] = $request->kategori;
        $utangData['nama_sales'] = $request->nama_sales;
        $utangData['tanggal_log'] = Carbon::now();
        // Buat data piutang
        $utang = UtangModel::create($utangData);

        // Buat data kas_bank
        $kas_bank = kas_bank::create([
            'tanggal_bukti' => $request->tanggal_bukti,
            'nomor_bukti' => $request->nomor_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '1',
            'jumlah' => $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_user' => $request->nama_user,
            'nama_sales_utang' => $request->nama_sales,
            'tanggal_log' => Carbon::now(),
            'from' => "Utang",

            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank && $utang){
            return redirect('pinjaman-kategoriDetail/' . urlencode($request->nomor_bukti))
            ->with('success', 'Data telah disimpan.');
        }
    }

    public function editPinjaman($id)
    {
        $kas = akun_kas::all();
        $pinjaman = UtangModel::where('id',$id)->firstOrFail();
        $linkCategori = CategorieSupplierModel::all();
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();

        return view('pinjaman.edit',compact('pinjaman','kas','kategori','linkCategori'));
    }

    public function updatePinjaman(Request $request,$id)
    {
        $request->validate(
            [
                'jumlah' => 'required',

            ],[
                'jumlah.required' => 'Jumlah Wajib di isi'
            ]
        );

        $pinjaman = $request->except(['_token', '_method']);
        $valueJumlah = (int) str_replace(['Rp. ', '.'], '', $request->jumlah);

            $tanggal_log = UtangModel::where('id',$id)->value('tanggal_log');
            // dd($tanggal_log);
            $utang = UtangModel::where('id', $id)->update(
                [
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'jumlah' => $valueJumlah,
                    'kategori' => $request->kategori,
                    'nama_akun' => $request->nama_akun,
                    'nama_sales' => $request->nama_sales,
                    'nama_user' => $request->nama_user,
                    'tanggal_log' => Carbon::now(),
                    ]
                );
            $kas_bank = kas_bank::where('tanggal_log', $tanggal_log)->update(
                [
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'jumlah' =>(-1) *  $valueJumlah,
                    'kategori' => $request->kategori,
                    'subcategories_id' => '2',
                    'nama_user' => $request->nama_user,
                    'nama_akun' => $request->nama_akun,
                    'nama_sales_utang' => $request->nama_sales,
                    'from' => "Utang",
                    'tanggal_log' => Carbon::now(),
                    ]
                );
                if($utang && $kas_bank){
                    return redirect()->route('utang.index')->with('success', 'Data telah di ubah.');  
                }

    }

    
    public function destroyPinjaman($id)  
    {
        $pinjaman = UtangModel::where('id', $id)->firstOrFail();
        $nama_akun = $pinjaman->nama_akun;
        $kategori = $pinjaman->kategori;
        $tanggal_log = $pinjaman->tanggal_log;
    
        // Mengambil data 'kas_bank' dengan tanggal_log terdekat dengan tanggal_log dari 'pinjaman'
        $kas_bank = kas_bank::where('tanggal_log', '>=', $tanggal_log)
        ->where('nama_akun',$nama_akun)
        ->where('kategori',$kategori)
        ->first(); 
    
        if ($kas_bank) {
            $kas_bank->delete();
        }
    
        $pinjaman->delete();
    
        return redirect()->route('utang.index')->with('success', 'Data Berhasil di hapus');
        // return redirect()->route('piutang.index', ['nama_akun' => $nama_akun])->with('success', 'Data Berhasil di hapus');
    }
    public function destroyPinjamanKategori($id)  
    {
        $pinjaman = UtangModel::where('id', $id)->firstOrFail();
        $oldKategori = $pinjaman->kategori;
        $nama_akun = $pinjaman->nama_akun;
        $kategori = $pinjaman->kategori;
        $tanggal_log = $pinjaman->tanggal_log;
    
        // Mengambil data 'kas_bank' dengan tanggal_log terdekat dengan tanggal_log dari 'pinjaman'
        $kas_bank = kas_bank::where('tanggal_log', '>=', $tanggal_log)
        ->where('nama_akun',$nama_akun)
        ->where('kategori',$kategori)
        ->first(); 
    
        if ($kas_bank) {
            $kas_bank->delete();
        }
    
        $pinjaman->delete();
    
        return redirect('pinjaman-kategoriDetail/' . urlencode($pinjaman->nomor_bukti))
        ->with('success', 'Data telah dihapus.');
                // return redirect()->route('piutang.index', ['nama_akun' => $nama_akun])->with('success', 'Data Berhasil di hapus');
    }
}
