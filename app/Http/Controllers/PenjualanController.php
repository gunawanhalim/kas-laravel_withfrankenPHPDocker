<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\penjualan;
use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use App\Models\pelanggan;
use App\Models\piutang;
use App\Models\subcategories;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $kas = akun_kas::paginate(25);
                $linkCategori = CategorieSupplierModel::all();

        // Query dasar untuk piutang dengan relasi ke kas_bank
        $query = penjualan::query();
    
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
        $linkCategori = CategorieSupplierModel::all();

        return view('penjualan.index', compact('kas', 'sales','linkCategori'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kas = akun_kas::all();
        $sales = penjualan::all();
        $pelanggan = pelanggan::all();
        $supplier = CategorieSupplierModel::where('kategori', "Supplier")->get();
        $linkCategori = CategorieSupplierModel::all();

        return view('penjualan.add',compact('sales','kas','pelanggan','supplier','linkCategori'));
    }

    public function searchPelanggan(Request $request)
    {
        $search = $request->input('q');

        $result = Pelanggan::where('nama_pelanggan', 'LIKE', "%{$search}%")
                            ->get(['nama_pelanggan', 'alamat']);

        return response()->json($result);
    }

    public function searchNomorNota(Request $request)
    {
        $search = $request->input('q');

        $result = penjualan::where('nomor_nota', 'LIKE', "%{$search}%")
                            ->get(['nomor_nota','nama_pelanggan','total']);

        return response()->json($result);
    }
    public function searchKategori(Request $request)
    {
        $search = $request->input('q');
    
        $results = subcategories::where('name', 'LIKE', "%{$search}%")
                                ->select('kategori_id', 'name')
                                ->get();
    
        return response()->json($results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'tanggal_nota' => 'required',
            'nama_pelanggan' => 'required',
            'alamat' => 'required',
            'nama_sales' => 'required',
            'kategori' => 'required',
            'nomor_nota' => 'required|unique:penjualan',

            // 'nomor_nota' => $request->has('generate_nomor') ? 'nullable' : 'required|unique:penjualan', // Tambahkan validasi dinamis untuk nomor_nota
        ];
    
        $messages = [
            'tanggal_nota.required' => 'Tanggal nota wajib diisi',
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi',
            'alamat.required' => 'Alamat pelanggan wajib diisi',
            'nama_sales.required' => 'Nama Supplier wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'nomor_nota.required' => 'Nomor nota wajib diisi',
            'nomor_nota.unique' => 'Nomor nota sudah ada, silakan coba lagi.',
        ];
    
        $validator = Validator::make($request->all(), $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        DB::beginTransaction();
        
        try {
            // $generateNomor = $request->has('generate_nomor');
    
            // // Jika generate_nomor tidak dicentang, gunakan nomor_nota dari request
            // if ($generateNomor) {
            //     $newNomorNota = $request->nomor_nota;
            // } else {
            //     // Jika generate_nomor dicentang, generate nomor nota baru
            //     $newNomorNota = $this->generateNomorNota();
            // }
    
            // // Cek apakah nomor nota sudah ada jika generate_nomor tidak dicentang
            // if (!$generateNomor && penjualan::where('nomor_nota', $newNomorNota)->exists()) {
            //     throw new \Exception('Nomor nota sudah ada, silakan coba lagi.');
            // }
    
            $lastNomorBukti = piutang::orderBy('nomor_bukti', 'desc')->first();
            $lastNumber = $lastNomorBukti ? (int) substr($lastNomorBukti->nomor_bukti, -6) : 0;
            $newNumber = $lastNumber + 1;
            $newNomorBukti = 'BKM.' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
            
            $total = (int) str_replace(['IDR ', '.'], '', $request->total);
            
            // Simpan data penjualan
            $sales = penjualan::create([
                'nomor_nota' => $request->nomor_nota,
                'tanggal_nota' => $request->tanggal_nota,
                'nama_pelanggan' => $request->nama_pelanggan,
                'alamat' => $request->alamat,
                'nama_sales' => $request->nama_sales,
                'total' => $total,
                'nama_user' => Auth::user()->username,
                'tanggal_log' => Carbon::now(),
                'jatuh_tempo' => $request->jatuh_tempo,
            ]);
    
            // Simpan data piutang
            $piutang = [
                'nomor_bukti' => $newNomorBukti,
                'tanggal_bukti' => $request->tanggal_nota,
                'jumlah' => $total,
                'kategori' => $request->kategori,
                'tanggal_log' => Carbon::now(),
                'nama_user' => Auth::user()->username,
                'nomor_nota' => $request->nomor_nota,
                'nama_pelanggan' => $request->nama_pelanggan,
                'nama_akun' => "-",
                'jatuh_tempo' => $request->jatuh_tempo,
            ];
            $piutang = piutang::create($piutang);
    
            if ($sales && $piutang) {
                DB::commit();
                return redirect('/penjualan')->with('success', 'Data telah disimpan.');
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
        $lastNomorNota = penjualan::where('nomor_nota', 'like', 'NNP-' . $datePart . '-%')
                                    ->orderBy('nomor_nota', 'desc')
                                    ->first();
    
        if ($lastNomorNota) {
            $lastNumber = (int) substr($lastNomorNota->nomor_nota, -6);
        } else {
            $lastNumber = 0;
        }
    
        $newNumber = $lastNumber + 1;
        $newNomorNota = 'NNP-' . $datePart . '-' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    
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

        $sale = penjualan::findOrFail($nomor_nota);
        return view('penjualan.show', compact('sale','kas','linkCategori'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nomor_nota)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        $sale = penjualan::findOrFail($nomor_nota);
        return view('penjualan.edit', compact('sale','kas','linkCategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $nomor_nota)
    {
        $request->validate([
            'tanggal_nota' => 'required',
            'nama_pelanggan' => 'required',
            // 'alamat' => 'required',
            'nama_sales' => 'required',
            'total' => 'required',
        ]);
    
        $total = (int) str_replace(['Rp. ', '.'], '', $request->total);
        $penjualan = penjualan::findOrFail($nomor_nota);
        $oldTanggalLog = $penjualan->tanggal_log;

        // Periksa apakah nama_pelanggan sama dengan yang ada di database
        if ($penjualan->nama_pelanggan !== $request->nama_pelanggan) {
            // Jika berbeda, update nama_pelanggan dan alamat
            $penjualan->nama_pelanggan = $request->nama_pelanggan;
            $penjualan->alamat = $request->alamat;
        } else {
            // Jika sama, hanya update nama_pelanggan
            $penjualan->nama_pelanggan = $request->nama_pelanggan;
        }
    
        $penjualan->tanggal_nota = $request->tanggal_nota;
        $penjualan->jatuh_tempo = $request->jatuh_tempo;
        $penjualan->tanggal_log = Carbon::now();
        $penjualan->nama_sales = $request->nama_sales;
        $penjualan->total = $total;
        $penjualan->nama_user = Auth::user()->username;
        $penjualan->save();

        $piutang = piutang::where('nomor_nota', $nomor_nota)
                            ->where('tanggal_log',$oldTanggalLog)->first();
        if ($piutang) {
            $piutang->jumlah = $total;
            $piutang->jatuh_tempo = $request->jatuh_tempo;
            $piutang->nama_pelanggan = $request->nama_pelanggan;
            $piutang->tanggal_log = Carbon::now();
            $piutang->nama_user = Auth::user()->username;
            $piutang->save();
        }else{
            return redirect('penjualan')->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
        return redirect('penjualan')->with('success', 'Penjualan dengan Nomor Nota: '. $nomor_nota .' berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $nomor_nota)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        $sale = penjualan::findOrFail($nomor_nota);
        return view('penjualan.delete', compact('sale','kas','linkCategori'));
    }
    public function destroy(string $nomor_nota)
    {
        $sale = penjualan::findOrFail($nomor_nota);
        $sale->delete();

        return redirect('penjualan')->with('success', 'Penjualan dengan Nomor Nota: '. $nomor_nota .' berhasil di hapus.');
    }

    public function detailCategoriPiutang($nomor_bukti)
    {
        $kas = akun_kas::all();
        $detailKategori = piutang::where('nomor_bukti',$nomor_bukti)->paginate(25);
        $category = $detailKategori->first();
        // dd($detailKategori);
        $sales = penjualan::all();
        $pelanggan = pelanggan::all();
        $kategori = CategorieSupplierModel::where('kategori',"Supplier")->get();
        $linkCategori = CategorieSupplierModel::all();

        return view('categories.detailKategoriPiutang',compact('sales','nomor_bukti','kas','pelanggan','linkCategori','detailKategori','category'));
    }
}
