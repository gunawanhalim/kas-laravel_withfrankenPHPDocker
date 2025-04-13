<?php

namespace App\Http\Controllers;

use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use App\Models\pelanggan;
use App\Models\PelangganPembelianModel;
use App\Models\piutang;
use App\Models\subcategories;
use App\Models\UtangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function indexPelanggan(Request $request)
    {
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        // Query dasar untuk pelanggan
        $query = pelanggan::query();
    
        if ($request->has('search')) {
            $search = $request->search;
            $category = $request->category;
            
            $query->where(function ($q) use ($search, $category) {
                if ($category) {
                    $q->where($category, 'like', '%' . $search . '%');
                } else {
                    $q->where('nama_pelanggan', 'like', '%' . $search . '%')
                        ->orWhere('alamat', 'like', '%' . $search . '%');
                }
            });
        }
    
        $pelanggan = $query->paginate(25);
    
        return view('pelanggan_penjualan.index', compact('kas', 'pelanggan','linkCategori'));
    }

    public function indexPembelian(Request $request)
    {
        $kas = akun_kas::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        // Query dasar untuk pelanggan
        $query = PelangganPembelianModel::query();
    
        if ($request->has('search')) {
            $search = $request->search;
            $category = $request->category;
            
            $query->where(function ($q) use ($search, $category) {
                if ($category) {
                    $q->where($category, 'like', '%' . $search . '%');
                } else {
                    $q->where('nama_pelanggan', 'like', '%' . $search . '%')
                        ->orWhere('alamat', 'like', '%' . $search . '%');
                }
            });
        }
    
        $pelanggan = $query->paginate(25);
    
        return view('pelanggan_pembelian.index', compact('kas', 'pelanggan','linkCategori'));
    }

    public function searchPelanggan(Request $request)
    {
        $search = $request->input('q');
    
        $result = PelangganPembelianModel::where('nama_pelanggan', 'LIKE', "%{$search}%")
                            ->orWhere('alamat', 'LIKE', "%{$search}%")
                            ->get(['nama_pelanggan', 'alamat']);
    
        return response()->json($result);
    }
    

    public function addPelangganstorePembelian(Request $request)
    {   
        // Buat validator
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => 'required|max:255',
            'alamat' => 'required|max:255',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Jika validasi berhasil, lakukan operasi yang di simpan ke database
        PelangganPembelianModel::create($request->all());
        return redirect()->back()->with('success', 'Data telah disimpan.');    
    }

    public function updatePelangganPembelian(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required',
        ]);

        $pelanggan = PelangganPembelianModel::findOrFail($id);
        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->save();

        return redirect()->back()->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    public function destroyPelangganPembelian($id)
    {
        $pelanggan = PelangganPembelianModel::where('id',$id);

        $pelanggan->delete();

        return redirect()->back();
    }

    public function linkDetailPenjualan(Request $request, $pelanggan, $nomor_bukti)
    {
        $kas = akun_kas::paginate(25);
        $decodedPelanggan = urldecode($pelanggan);
        $linkCategori = CategorieSupplierModel::all();
        // dd($pelanggan, $nomor_bukti);
        $query = piutang::where('nama_pelanggan', $pelanggan)->where('nomor_bukti', $nomor_bukti);
    
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', '%' . $search . '%')
                    ->orWhere('nomor_nota', 'like', '%' . $search . '%')
                    ->orWhere('jumlah', 'like', '%' . $search . '%')
                    ->orWhere('nama_akun', 'like', '%' . $search . '%');
            });
        }
    
        if ($request->has('column') && $request->has('order')) {
            $column = $request->input('column');
            $order = $request->input('order');
            $query->orderBy($column, $order);
        } else {
            $query->orderBy('tanggal_bukti', 'asc');
        }
    
        $piutang = $query->paginate(25);
    
        // Ambil nomor_bukti dari entri pertama jika ada
        $firstNomorBukti = $piutang->first() ? $piutang->first()->nomor_bukti : null;
    
        return view('kategori_pelanggan.kategori_pelanggan_penjualan', compact('linkCategori', 'decodedPelanggan', 'kas', 'piutang', 'firstNomorBukti'));
    }
    public function linkDetailPembelian(Request $request, $sales, $nomor_bukti)
    {           
        $kas = akun_kas::paginate(25);
        $decodedSales = urldecode($sales);
        $linkCategori = CategorieSupplierModel::all();
    
        $query = UtangModel::where('nama_sales', $sales, 'and')->where('nomor_bukti', $nomor_bukti);
    
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', '%' . $search . '%')
                    ->orWhere('nomor_nota', 'like', '%' . $search . '%')
                    ->orWhere('jumlah', 'like', '%' . $search . '%')
                    ->orWhere('nama_sales', 'like', '%' . $search . '%')
                    ->orWhere('nama_akun', 'like', '%' . $search . '%');
            });
        }
    
        if ($request->has('column') && $request->has('order')) {
            $column = $request->input('column');
            $order = $request->input('order');
            $query->orderBy($column, $order);
        } else {
            $query->orderBy('tanggal_bukti', 'asc');
        }
    
        $utang = $query->paginate(25);
    
        // Ambil nomor_bukti dari entri pertama jika ada
        $firstNomorBukti = $utang->first() ? $utang->first()->nomor_bukti : null;
    
        return view('kategori_pelanggan.kategori_sales_pembelian', compact('linkCategori', 'decodedSales', 'kas', 'utang', 'firstNomorBukti'));
    }

    public function createPembayaranBayar()
    {

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $pelanggan = pelanggan::all();
        $allPelanggan = piutang::distinct()->pluck('nama_pelanggan');
        $allNamaAkun = akun_kas::distinct()->pluck('nama_akun');
        $allKategori = CategorieSupplierModel::distinct()->pluck('name');
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        // $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('kategori_pelanggan.createBayar', compact('kas','kategori','linkCategori','pelanggan','allPelanggan','allNamaAkun','allKategori'));
    }
    public function createPembelianBayar()
    {

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $pelanggan = pelanggan::all();
        $allPelanggan = UtangModel::distinct()->pluck('nama_sales');
        $allNamaAkun = akun_kas::distinct()->pluck('nama_akun');
        $allKategori = CategorieSupplierModel::distinct()->pluck('name');
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        // $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('kategori_pelanggan.createPembelianBayar', compact('kas','kategori','linkCategori','pelanggan','allPelanggan','allNamaAkun','allKategori'));
    }
    public function createPembelianTambah()
    {

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $pelanggan = pelanggan::all();
        $allPelanggan = UtangModel::distinct()->pluck('nama_sales');
        $allNamaAkun = akun_kas::distinct()->pluck('nama_akun');
        $allKategori = CategorieSupplierModel::distinct()->pluck('name');
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        // $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('kategori_pelanggan.createPembelianTambah', compact('kas','kategori','linkCategori','pelanggan','allPelanggan','allNamaAkun','allKategori'));
    }

    public function searchPelangganPiutang(Request $request)
    {
        $search = $request->input('q');
    
        // Query untuk mendapatkan data dengan nama_akun yang sama dengan "-"
        $result = piutang::where('nama_akun', '-') // Pastikan nama_akun adalah "-"
            ->where(function ($query) use ($search) {
                $query->where('nomor_bukti', 'LIKE', "%{$search}%")
                    ->orWhere('nama_pelanggan', 'LIKE', "%{$search}%");
            })
            ->get(['nama_pelanggan', 'nomor_bukti', 'nomor_nota', 'nama_akun', 'kategori']);
    
        return response()->json($result);
    }
    public function searchPelangganUtang(Request $request)
    {
        $search = $request->input('q');
    
        // Query untuk mendapatkan data dengan nama_akun yang sama dengan "-"
        $result = UtangModel::where('nama_akun', '-') // Pastikan nama_akun adalah "-"
            ->where(function ($query) use ($search) {
                $query->where('nomor_bukti', 'LIKE', "%{$search}%")
                    ->orWhere('nama_sales', 'LIKE', "%{$search}%");
            })
            ->get(['nama_sales', 'nomor_bukti', 'nomor_nota', 'nama_akun', 'kategori']);
    
        return response()->json($result);
    }

    public function createPembayaranTambah()
    {

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $pelanggan = pelanggan::all();
        $allPelanggan = piutang::distinct()->pluck('nama_pelanggan');
        $allNamaAkun = akun_kas::distinct()->pluck('nama_akun');
        $allKategori = CategorieSupplierModel::distinct()->pluck('name');
        // $pembayaran = piutang::find($nomor_bukti);
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        // $pembayaran = piutang::where('nomor_bukti', $nomor_bukti)->firstOrFail();
        return view('kategori_pelanggan.createTambah', compact('kas','kategori','linkCategori','pelanggan','allPelanggan','allNamaAkun','allKategori'));
    }

    public function storePembayaranBayar(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            'nama_pelanggan' => 'required',
            'tanggal_bukti' => 'required',
        ],[
            // 'kategori.required' => 'Kategori Wajib di isi',
            'nomor_bukti.required' => 'Nomor Bukti Wajib di isi',
            'nama_akun.required' => 'Nama Akun Wajib di isi',
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
            'nomor_bukti' => $request->nomor_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '1',
            'jumlah' => $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            'from' => "Piutang",
            'jatuh_tempo' => $request->jatuh_tempo,

            
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank){
            return redirect('detail-penjualan-pelanggan/nama_pelanggan=' . $request->nama_pelanggan . '&nomor_bukti=' . $request->nomor_bukti)->with('success', 'Data telah disimpan.');       
        }else{
            return redirect('detail-penjualan-pelanggan/nama_pelanggan=' . $request->nama_pelanggan . '&nomor_bukti=' . $request->nomor_bukti)->with('error', 'Data gagal disimpan.');
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
            'nomor_bukti' => $request->nomor_bukti,
            'nama_akun' => $nama_akun,
            'subcategories_id' => '2',
            'jumlah' => (-1) * $valueJumlah,
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            'from' => "Piutang",

            'jatuh_tempo' => $request->jatuh_tempo,

            
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank){
            return redirect('detail-penjualan-pelanggan/nama_pelanggan=' . $request->nama_pelanggan . '&nomor_bukti=' . $request->nomor_bukti)->with('success', 'Data telah disimpan.');  
        }
    }
    public function storePembelianBayar(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            'nama_sales' => 'required',
            'tanggal_bukti' => 'required',
        ],[
            // 'kategori.required' => 'Kategori Wajib di isi',
            'nomor_bukti.required' => 'Nomor Bukti Wajib di isi',
            'nama_akun.required' => 'Nama Akun Wajib di isi',
            'tanggal_bukti.required' => 'Tanggal Bukti Wajib di isi',
        ]
        );
        
        // dd($request->kategori);
        $nama_akun = $request->nama_akun;
        $valueJumlah = (int) str_replace(['IDR ', '.'], '', $request->jumlah);
        // if ($valueJumlah < 0) {
        //     $subcategories_id = 2;
        //     $jumlah = (-1) * $valueJumlah; // Jika negatif, ubah jumlah ke positif
        // } else {
        //     $subcategories_id = 1;
        //     $jumlah = $valueJumlah; // Jika positif, gunakan nilai jumlah langsung
        // }
        $utangData = $request->all();
        $utangData['jumlah'] = (-1) * $valueJumlah;
        $utangData['kategori'] = $request->kategori;
        $utangData['tanggal_log'] = Carbon::now();
        $utangData['jatuh_tempo'] = $request->jatuh_tempo;

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
            'nama_sales_utang' => $request->nama_sales,
            'nama_user' => $request->nama_user,
            'tanggal_log' => Carbon::now(),
            'from' => "Utang",
            'jatuh_tempo' => $request->jatuh_tempo,

            
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank){
            return redirect('detail-pembelian-pelanggan/nama_supplier=' . $request->nama_sales . '&nomor_bukti=' . $request->nomor_bukti)->with('success', 'Data telah diubah.');       
        }
    }
    public function storePembelianTambah(Request $request)
    {
        $request->validate([
            'nomor_bukti' => 'required',
            'nama_akun' => 'required|string',
            'nama_user' => 'required|string',
            'nomor_nota' => 'required',
            // 'kategori' => 'required',
            'jumlah' => 'required',
            'nama_sales' => 'required',
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
        $utangData['tanggal_log'] = Carbon::now();
        $utangData['jatuh_tempo'] = $request->jatuh_tempo;

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
            'jatuh_tempo' => $request->jatuh_tempo,

            
            // // Sisipkan id piutang sebagai foreign key
            // 'piutang_id' => $piutang->id,
        ]);
        if($kas_bank){
            return redirect('detail-pembelian-pelanggan/nama_supplier=' . $request->nama_sales . '&nomor_bukti=' . $request->nomor_bukti)->with('success', 'Data telah diubah.');  
        }
    }

    public function editPembayaran($id)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        $pembayaran = piutang::where('id',$id)->firstOrFail();
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        return view('kategori_pelanggan.edit',compact('pembayaran','kas','kategori','linkCategori'));
    }

    public function editPembelian($id)
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        $pembelian = UtangModel::where('id',$id)->firstOrFail();
        $kategori = CategorieSupplierModel::where('kategori','Supplier')->get();
        return view('kategori_pelanggan.editPembelian',compact('pembelian','kas','kategori','linkCategori'));
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
        if ($valueJumlah < 0) {
            $subcategories_id = 2;
            $jumlah = (-1) * $valueJumlah; // Jika negatif, ubah jumlah ke positif
        } else {
            $subcategories_id = 1;
            $jumlah = $valueJumlah; // Jika positif, gunakan nilai jumlah langsung
        }
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
                    'nomor_bukti' => $request->nomor_bukti,
                    'nama_akun' => $request->nama_akun,
                    'jumlah' => $valueJumlah,
                    'subcategories_id' => $subcategories_id,
                    'kategori' => $request->kategori,
                    'nama_user' => $request->nama_user,
                    'nama_pelanggan' => $request->nama_pelanggan,
                    'from' => "Piutang",
                    'tanggal_log' => Carbon::now(),
                    ]
                );
                if($piutang && $kas_bank){
                    return redirect('detail-penjualan-pelanggan/nama_pelanggan=' . $request->nama_pelanggan . '&nomor_bukti=' . $request->nomor_bukti)->with('success', 'Data telah diubah.');                     
                }

    }
    public function updatePembelian(Request $request,$id)
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
        $tanggal_log = UtangModel::where('id',$id)->value('tanggal_log');
        // $subcategories_id = $valueJumlah < 0 ? 2 : 1;
        if ($valueJumlah < 0) {
            $subcategories_id = 2;
            $jumlah = (-1) * $valueJumlah; // Jika negatif, ubah jumlah ke positif
        } else {
            $subcategories_id = 1;
            $jumlah = $valueJumlah; // Jika positif, gunakan nilai jumlah langsung
        }
            // dd($tanggal_log);
            $utang = UtangModel::where('id', $id)->update(
                [
                    'nama_akun' => $request->nama_akun,
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'jumlah' => $valueJumlah,
                    'kategori' => $request->kategori,
                    'nama_user' => $request->nama_user,
                    'nama_sales' => $request->nama_sales,
                    'tanggal_log' => Carbon::now(),
                    ]
                );
            
                $kas_bank = kas_bank::where('tanggal_log', $tanggal_log)->update(
                    [
                    'tanggal_bukti' => $request->tanggal_bukti,
                    'nama_akun' => $request->nama_akun,
                    'jumlah' => $valueJumlah,
                    'subcategories_id' => $subcategories_id,
                    'kategori' => $request->kategori,
                    'nama_user' => $request->nama_user,
                    'nama_sales_utang' => $request->nama_sales,
                    'from' => "Utang",
                    'tanggal_log' => Carbon::now(),
                    ]
                );
                if($utang && $kas_bank){
                    return redirect('detail-pembelian-pelanggan/nama_supplier=' . $request->nama_sales . '&nomor_bukti=' . $request->nomor_bukti)->with('success', 'Data telah diubah.');                    
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
        return redirect('detail-penjualan-pelanggan/nama_pelanggan=' . $pembayaran->nama_pelanggan . '&nomor_bukti=' . $pembayaran->nomor_bukti)->with('success', 'Data telah dihapus.');     
    }
    public function destroyPembelian($id)  
    {
        $pembelian = UtangModel::where('id', $id)->firstOrFail();
        $nama_akun = $pembelian->nama_akun;
        $kategori = $pembelian->kategori;
        $tanggal_log = $pembelian->tanggal_log;
    
        // Mengambil data 'kas_bank' dengan tanggal_log terdekat dengan tanggal_log dari 'pembelian'
        $kas_bank = kas_bank::where('tanggal_log', '>=', $tanggal_log)
        ->where('nama_akun',$nama_akun)
        ->where('kategori',$kategori)
        ->first(); 
    
        if ($kas_bank) {
            $kas_bank->delete();
        }
    
        $pembelian->delete();
    
        return redirect('detail-pembelian-pelanggan/nama_pelanggan=' . $pembelian->nama_pelanggan . '&nomor_bukti=' . $pembelian->nomor_bukti)->with('success', 'Data telah dihapus.');     
    }
    
}
