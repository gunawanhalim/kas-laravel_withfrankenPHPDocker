<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\pelanggan;
use App\Models\PelangganPembelianModel;
use App\Models\PembelianModel;
use App\Models\UtangModel;
use Carbon\Carbon;
use Dotenv\Parser\Value;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        // Query dasar untuk piutang dengan relasi ke kas_bank
        $query = PembelianModel::query();
    
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
            $query->whereBetween('tanggal_nota', [$startDate, $endDate]);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_nota', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $search . '%')
                    ->orWhere('nama_sales', 'like', '%' . $search . '%')
                    ->orWhere('nama_user', 'like', '%' . $search . '%');
            });
        }


        // Urutan berdasarkan kolom dan arah
        if ($request->has('column') && $request->has('order')) {
            $column = $request->input('column');
            $order = $request->input('order');
            $query->orderBy($column, $order);
        } else {
            $query->orderBy('tanggal_nota', 'desc'); // Default sorting
        }
    
        // Ambil data dengan paginasi
        $sales = $query->paginate(25);
    
        return view('pembelian.index', compact('kas', 'sales','linkCategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kas = akun_kas::all();
        $sales = PembelianModel::all();
        $pelanggan = PelangganPembelianModel::all();
        $kategori = CategorieSupplierModel::where('kategori',"Supplier")->get();
        $linkCategori = CategorieSupplierModel::all();

        return view('pembelian.add',compact('sales','kas','pelanggan','kategori','linkCategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'nomor_nota' => 'required|unique:pembelian',
            'tanggal_nota' => 'required',
            'jatuh_tempo' => 'required',
            // 'nama_pelanggan' => 'required',
            // 'alamat' => 'required',
            'nama_sales' => 'required',
            'kategori' => 'required',
        ];

        $messages = 
        [
            'nomor_nota.unique' => 'Nomor nota tidak boleh sama dengan sebelumnya',
            'nomor_nota.required' => 'Nomor nota wajib diisi',
            'tanggal_nota.required' => 'Tanggal nota wajib diisi',
            // 'nama_pelanggan.required' => 'Nama pelanggan wajib diisi',
            // 'alamat.required' => 'Alamat pelanggan wajib diisi',
            'nama_sales.required' => 'Nama Supplier wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        DB::beginTransaction();
    
        try {
            $lastNomorBukti = UtangModel::orderBy('nomor_bukti', 'desc')->first();
    
            if ($lastNomorBukti) {
                $lastNumber = (int) substr($lastNomorBukti->nomor_bukti, -6);
            } else {
                $lastNumber = 0;
            }
    
            $newNumber = $lastNumber + 1;
            $newNomorBukti = 'BKK.' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    
            $total = (int) str_replace(['IDR ', '.'], '', $request->total);
            $sales = PembelianModel::create([
                'nomor_nota' => $request->nomor_nota,
                'tanggal_nota' => $request->tanggal_nota,
                // 'nama_pelanggan' => $request->nama_pelanggan,
                // 'alamat' => $request->alamat,
                'nama_sales' => $request->nama_sales,
                'total' => $total,
                'nama_user' => Auth::user()->username,
                'tanggal_log' => Carbon::now(),
                'jatuh_tempo' => $request->jatuh_tempo,

            ]);
    
            $nama_akun = $request->nama_akun;
            $utangData = [
                'nomor_bukti' => $newNomorBukti,
                'tanggal_bukti' => $request->tanggal_nota,
                'jumlah' => $total,
                'kategori' => $request->kategori,
                'tanggal_log' => Carbon::now(),
                'nama_user' => Auth::user()->username,
                'nomor_nota' => $request->nomor_nota,
                'nama_sales' => $request->nama_sales,
                'nama_akun' => "-",
                'jatuh_tempo' => $request->jatuh_tempo,

            ];
            $utang = UtangModel::create($utangData);
    
            if ($sales && $utang) {
                DB::commit();
                return redirect('/pembelian')->with('success', 'Data telah disimpan.');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function generateNomorNota()
    {
        $datePart = Carbon::now()->format('Ymd');
        $lastNomorNota = PembelianModel::where('nomor_nota', 'like', 'NNU-' . $datePart . '-%')
                                    ->orderBy('nomor_nota', 'desc')
                                    ->first();
    
        if ($lastNomorNota) {
            $lastNumber = (int) substr($lastNomorNota->nomor_nota, -6);
        } else {
            $lastNumber = 0;
        }
    
        $newNumber = $lastNumber + 1;
        $newNomorNota = 'NNU-' . $datePart . '-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    
        return $newNomorNota;
    }

    public function generateNomorNotaJson()
    {
        $newNomorNota = $this->generateNomorNota();
        return response()->json(['nomor_nota' => $newNomorNota]);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($nomor_nota)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        $sale = PembelianModel::findOrFail($nomor_nota);
        return view('pembelian.show', compact('sale','kas','linkCategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nomor_nota)
    {
        $kas = akun_kas::all();
        $sale = PembelianModel::findOrFail($nomor_nota);
        $linkCategori = CategorieSupplierModel::all();

        return view('pembelian.edit', compact('sale','kas','linkCategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nomor_nota)
    {
        // Validasi input
        $request->validate([
            'tanggal_nota' => 'required',
            'nama_sales' => 'required',
            'total' => 'required',
        ]);

        // Format total
        $total = (int) str_replace(['Rp. ', '.'], '', $request->total);

        // Temukan pembelian berdasarkan nomor nota
        $pembelian = PembelianModel::findOrFail($nomor_nota);
        $oldTanggalLog = $pembelian->tanggal_log;
        // Update entri pembelian
        $pembelian->tanggal_nota = $request->tanggal_nota;
        $pembelian->jatuh_tempo = $request->jatuh_tempo;
        $pembelian->tanggal_log = Carbon::now();
        $pembelian->nama_sales = $request->nama_sales;
        $pembelian->total = $total;
        $pembelian->nama_user = Auth::user()->username;
        $pembelian->save();

        // Update atau buat entri utang
        $utang = UtangModel::where('nomor_nota', $nomor_nota)
                            ->where('tanggal_log',$oldTanggalLog)->first();
        // dd($utang);
        if ($utang) {
            // Jika entri utang ada, perbarui total dan informasi lainnya
            $utang->nomor_nota = $request->nomor_nota;
            $utang->tanggal_nota = $request->tanggal_nota;
            $utang->jatuh_tempo = $request->jatuh_tempo;
            $utang->tanggal_log = Carbon::now();
            $utang->jumlah = $total;
            $utang->jatuh_tempo = $request->jatuh_tempo;
            $utang->nama_user = Auth::user()->username;

            $utang->save();
        } else {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }

        // Redirect dengan pesan sukses
        return redirect('pembelian')->with('success', 'Pembelian dengan Nomor Nota: '. $nomor_nota .' berhasil diperbarui.');
    }
    

    public function delete(string $nomor_nota)
    {
        $kas = akun_kas::all();
        $sale = PembelianModel::findOrFail($nomor_nota);
        $linkCategori = CategorieSupplierModel::all();

        return view('pembelian.delete', compact('sale','kas','linkCategori'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nomor_nota)
    {
        $sale = PembelianModel::findOrFail($nomor_nota);
        $sale->delete();

        return redirect('pembelian')->with('success', 'Pembelian dengan Nomor Nota: '. $nomor_nota .' berhasil di hapus.');
    }


    public function detailCategoriHutang(Request $request, $nomor_bukti)
    {
        $kas = akun_kas::all();
        $query = UtangModel::where('nomor_bukti',$nomor_bukti);

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
            });
        }

        // Urutan berdasarkan kolom dan arah
        if ($request->has('column') && $request->has('order')) {
            $column = $request->input('column');
            $order = $request->input('order');
            $query->orderBy($column, $order);
        } else {
            $query->orderBy('tanggal_bukti', 'asc'); // Default sorting
        }
        $sales = PembelianModel::all();
        $pelanggan = PelangganPembelianModel::all();
        $kategori = CategorieSupplierModel::where('kategori',"Supplier")->get();
        $linkCategori = CategorieSupplierModel::all();
        $detailKategori = $query->paginate(25);
        $category = $detailKategori->first();
        return view('categories.detailKategoriUtang',compact('sales','nomor_bukti','kas','pelanggan','kategori','linkCategori','detailKategori','category'));
    }
}
