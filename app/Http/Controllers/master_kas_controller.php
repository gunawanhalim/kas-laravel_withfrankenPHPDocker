<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\akun_kas;
use App\Models\kas_bank;
use App\Models\pelanggan;
use App\Models\piutang;
use App\Models\subcategories;
use App\Models\User;
use PDF;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KasExport;
use App\Models\CategorieSupplierModel;
use App\Models\UtangModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class master_kas_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kas = akun_kas::all();
        $akun_kas = akun_kas::paginate(50);
        $pelanggan = pelanggan::paginate(25);
        $users = User::paginate(25);
        $linkCategori = CategorieSupplierModel::all();

        return view('bukuKas.index', compact('kas', 'pelanggan', 'akun_kas', 'users', 'linkCategori'));
    }
    public function sidebarindex()
    {
        $kas = akun_kas::all();
        $akun_kas = akun_kas::paginate(50);
        $pelanggan = pelanggan::paginate(25);
        $users = User::paginate(25);
        $linkCategori = CategorieSupplierModel::all();
        return view('bukuKas.sidebarindex', compact('kas', 'pelanggan', 'akun_kas', 'users', 'linkCategori'));
    }

    public function linkCategoriHutang(Request $request, $name)
    {
        // Ambil data kas dan kas_bank untuk ditampilkan di view
        $kas = akun_kas::paginate(25);
        $kas_bank = kas_bank::paginate(50);
        $decodedCategory = urldecode($name);
        $linkCategori = CategorieSupplierModel::all();

        $query = UtangModel::with('kas_bank')->where('kategori', $decodedCategory)
            ->selectRaw('
            nomor_bukti,kategori,
            SUM(jumlah) as total_jumlah,
            MAX(tanggal_bukti) as tanggal_bukti,
            MAX(nomor_nota) as nomor_nota,
            MAX(nama_sales) as nama_sales,
            MAX(nama_akun) as nama_akun,
            COALESCE(MAX(jatuh_tempo), \'No Date Available\') as jatuh_tempo
        ')
            ->groupBy('nomor_bukti', 'kategori');

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
                    ->orWhere('nama_sales', 'like', '%' . $search . '%')
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
        $hutang = $query->paginate(25);

        return view('categories.linkCategori', compact('linkCategori', 'decodedCategory', 'kas', 'hutang'));
    }

    public function linkCategoriPiutang(Request $request, $name)
    {
        $kas = akun_kas::paginate(25);
        $decodedCategory = urldecode($name);
        $linkCategori = CategorieSupplierModel::all();

        $query = piutang::with('kas_bank')->where('kategori', $decodedCategory)
            ->selectRaw('
                nomor_bukti,kategori,
                SUM(jumlah) as total_jumlah,
                MAX(tanggal_bukti) as tanggal_bukti,
                MAX(nomor_nota) as nomor_nota,
                MAX(nama_pelanggan) as nama_pelanggan,
                MAX(nama_akun) as nama_akun,
                COALESCE(MAX(jatuh_tempo), \'No Date Available\') as jatuh_tempo
            ')
            ->groupBy('nomor_bukti', 'kategori');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_bukti', 'like', '%' . $search . '%')
                    ->orWhere('nomor_nota', 'like', '%' . $search . '%')
                    ->orWhere('jumlah', 'like', '%' . $search . '%')
                    ->orWhere('nama_pelanggan', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%')
                    ->orWhere('nama_akun', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('column') && $request->has('order')) {
            $column = $request->input('column');
            $order = $request->input('order');
            $query->orderBy($column, $order);
        } else {
            $query->orderBy('nomor_bukti', 'asc');
        }

        $piutang = $query->paginate(25);

        return view('categories.linkCategoriPiutang', compact('linkCategori', 'decodedCategory', 'kas', 'piutang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('bukuKas.create', compact('kas', 'linkCategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Buat validator
        $validator = Validator::make($request->all(), [
            'nama_akun' => 'required|max:255|unique:akun_kas',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Jika validasi berhasil, lakukan operasi yang di simpan ke database
        if (Auth::user()->role == "Owner") {  
            akun_kas::create($request->all());
            return redirect()->back()->with('success', 'Data telah disimpan sebagai Owner');
        } elseif (Auth::user()->role == "Manager") {
            akun_kas::create($request->all());
            return redirect()->back()->with('success', 'Data telah disimpan sebagai Manager');
        }
    
        return redirect()->back()->with('error', 'Anda tidak memiliki akses');
    }

    public function addPelangganstore(Request $request)
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
        pelanggan::create($request->all());
        return redirect()->back()->with('success', 'Data telah disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function filterData(Request $request, $id_kas)
    {
        $query = kas_bank::where('nama_akun', $id_kas->nama_akun)->with('fromAccount', 'toAccount');
        $saldoAwal = 0;

        if ($request->has('category')) {
            $query->where('kategori', $request->category);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('tanggal_bukti', [$startDate, $endDate]);

            $saldoAwal = kas_bank::where('nama_akun', $id_kas->nama_akun)
                ->where('tanggal_bukti', '<', $startDate)
                ->sum('jumlah');
        } else {
            $saldoAwal = kas_bank::where('nama_akun', $id_kas->nama_akun)
                ->orderBy('tanggal_bukti', 'asc')
                ->value('jumlah');
        }

        $filteredData = $query->with(['categories.subcategories'])->paginate(25);

        return [
            'filteredData' => $filteredData,
            'saldoAwal' => $saldoAwal
        ];
    }
    public function show(Request $request, $nama_akun)
    {
        $kas = akun_kas::all();
        $id_kas = akun_kas::where('nama_akun', $nama_akun)->first();
        if (!$id_kas) {
            abort(404); // Tampilkan halaman 404 jika data tidak ditemukan
        }
        // if ($id_kas) {
        //     dd($id_kas);
        // } else {
        //     dd('Data tidak ditemukan atau nama_akun tidak cocok.');
        // }
        $nama_akun = $id_kas->nama_akun;

        // Calculate overall balance (saldoAkhir) without any filters
        $allKasBank = kas_bank::where('nama_akun', $id_kas->nama_akun)->get();
        $totalPemasukan = $allKasBank->where('subcategories_id', 1)->sum('jumlah');
        $totalPengeluaran = $allKasBank->where('subcategories_id', 2)->sum('jumlah');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Get filtered data
        $filteredDataResult = $this->filterData($request, $id_kas);
        $kas_bank = $filteredDataResult['filteredData'];
        $saldoAwal = $filteredDataResult['saldoAwal'];
        $semuaKas = kas_bank::all();

        // Calculate the balance for filtered data
        $groupedKasBank = $kas_bank->groupBy('nama_akun');
        $groupedKategori = $kas_bank->groupBy('kategori');
        // $totalPemasukanFiltered = $kas_bank->where('subcategories_id', 1)->sum('jumlah');
        // $totalPengeluaranFiltered = $kas_bank->where('subcategories_id', 2)->sum('jumlah');
        $totalPemasukanFiltered = 0;
        $totalPengeluaranFiltered = 0;
        $totalPemasukanSaldo = 0;
        $totalPengeluaranSaldo = 0;

        $saldo = $kas_bank->sum('jumlah');

        foreach ($groupedKategori as $kategori => $items) {
            $total = $items->sum('jumlah');
            if ($items->first()->subcategories_id == 1) {
                $totalPemasukanFiltered += $total;
            } elseif ($items->first()->subcategories_id == 2) {
                $totalPengeluaranFiltered += $total;
            }
        }
        foreach ($groupedKasBank as $kategori => $items) {
            $total = $items->sum('jumlah');
            if ($items->first()->subcategories_id == 1) {
                $totalPemasukanSaldo += $total;
            } elseif ($items->first()->subcategories_id == 2) {
                $totalPengeluaranSaldo += $total;
            }
        }

        $tanggal = Carbon::now()->format('Ymd_His');

        if ($request->has('kas_pdf')) {
            $pdf = FacadePdf::loadView('exports.kasPDF', compact('tanggal', 'groupedKategori', 'kas_bank', 'nama_akun', 'totalPemasukan', 'totalPengeluaran', 'saldo', 'saldoAwal', 'saldoAkhir', 'totalPemasukanFiltered', 'totalPengeluaranFiltered'));
            $pdf->setOption('title', 'Laporan Kas Bank PDF ' . Carbon::now()->format('d F Y'));
            return $pdf->download('laporan_' . $tanggal . '.pdf');
        }

        if ($request->has('kas_excel')) {
            return Excel::download(new KasExport($groupedKasBank, $kas_bank, $nama_akun, $totalPemasukanFiltered, $totalPengeluaranFiltered, $saldo, $saldoAwal, $saldoAkhir), 'laporan_' . $tanggal . '.xlsx');
        }

        $subcategories = subcategories::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('bukuKas.show', compact('id_kas', 'kas_bank', 'saldo', 'saldoAwal', 'saldoAkhir', 'kas', 'nama_akun', 'semuaKas', 'subcategories', 'linkCategori'));
    }
    public function dailyDownload(Request $request)
    {
        $date = $request->query('date'); // Tangkap tanggal dari query parameter

        // Validasi tanggal
        if (!$this->isValidDate($date)) {
            return response()->json(['error' => 'Tanggal tidak valid'], 400);
        }

        // Mengambil data dari model KasBank berdasarkan tanggal
        $data = kas_bank::whereDate('tanggal_bukti', $date)->get(); // Sesuaikan dengan nama kolom tanggal di tabel

        // Generate report
        $fileName = "report_{$date}.pdf"; // Nama file berdasarkan tanggal
        $filePath = storage_path("app/reports/{$fileName}");

        if (!file_exists($filePath)) {
            // Ciptakan PDF jika belum ada
            $this->createPdf($data, $filePath);
        }

        return response()->download($filePath);
    }

    private function isValidDate($date)
    {
        // Cek apakah tanggal dalam format yang valid (YYYY-MM-DD)
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    private function createPdf($data, $filePath)
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $pdf = new Dompdf($options);

        $html = view('exports.reports', compact('data'))->render();

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        // Ensure the reports directory exists
        $directoryPath = dirname($filePath);
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true); // Create the directory with proper permissions
        }

        file_put_contents($filePath, $pdf->output());
    }



    public function getSubcategories($type)
    {

        $subcategories = subcategories::where('kategori_id', $type)
            // ->where('nama_akun', $nama_akun)
            ->get();
        return response()->json($subcategories);
        // dd($subcategories);
    }

    public function laporan()
    {
        $kas = akun_kas::all();
        $id_kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('laporan.laporanKas', compact('id_kas', 'kas', 'linkCategori'));
    }

    public function dailyChart()
    {
        $kas = akun_kas::all();
        $id_kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $kas_bank = kas_bank::all();
        return view('laporan.laporanKas', compact('id_kas', 'kas', 'linkCategori', 'kas_bank'));
    }

    public function getDailyReport(Request $request)
    {
        // Get 'date' and 'nama_akun' from the query string, setting default date to today
        $date = $request->query('date', date('Y-m-d'));
        $nama_akun = $request->query('nama_akun');

        // Validate date and nama_akun
        if (!$date || !strtotime($date) || !$nama_akun) {
            return response()->json(['error' => 'Invalid date or nama_akun'], 400);
        }

        // Filter reports by date and nama_akun
        $reports = kas_bank::whereDate('tanggal_bukti', $date)
            ->where('nama_akun', $nama_akun)
            ->where('from', 'Kas')->orWhere('from', 'Utang')->orWhere('from', 'Piutang')
            ->get();

        $transfers = kas_bank::whereDate('tanggal_bukti', $date)
            ->where('nama_akun', $nama_akun)
            ->where('from', 'Transfer')
            ->get();

        // Calculate saldo_awal before the provided date for the specified akun
        $saldo_awal = kas_bank::where('tanggal_bukti', '<', $date)
            ->where('nama_akun', $nama_akun)
            ->sum('jumlah');

        // Calculate total pemasukan and pengeluaran for the filtered reports
        $jumlah_pemasukan = $reports->where('jumlah', '>', 0)->sum('jumlah');
        $jumlah_pengeluaran = $reports->where('jumlah', '<', 0)->sum('jumlah');

        // Group pemasukan and pengeluaran by kategori
        $kategori_pemasukan = $reports->where('subcategories_id', 1);
        $kategori_pengeluaran = $reports->where('subcategories_id', 2);

        $kategori_transfer_pemasukan = $transfers->where('subcategories_id', 1);
        $kategori_transfer_pengeluaran = $transfers->where('subcategories_id', 2);

        $pemasukan_per_kategori = $kategori_pemasukan->groupBy('kategori')->map(function ($items) {
            return $items->sum('jumlah');
        });

        $pengeluaran_per_kategori = $kategori_pengeluaran->groupBy('kategori')->map(function ($items) {
            return $items->sum(function ($item) {
                return abs((float) $item['jumlah']);
            });
        });
        // Group pemasukan by kategori and date
        $pemasukan_per_kategori_per_tanggal = $kategori_pemasukan->groupBy('kategori')->map(function ($items) {
            return $items->groupBy(function ($item) {
                return $item->tanggal_bukti; // Group by date
            })->map(function ($dateItems) {
                return $dateItems->sum('jumlah'); // Sum per date
            });
        });

        // Group pengeluaran by kategori and date
        $pengeluaran_per_kategori_per_tanggal = $kategori_pengeluaran->groupBy('kategori')->map(function ($items) {
            return $items->groupBy(function ($item) {
                return $item->tanggal_bukti; // Group by date
            })->map(function ($dateItems) {
                return $dateItems->sum('jumlah'); // Sum per date, absolute for pengeluaran
            });
        });

        $kategoriTransferMasuk = $kategori_transfer_pemasukan->groupBy('to_account_id')->map(function ($items) {
            return $items->groupBy(function ($item) {
                return $item->tanggal_bukti;
            })->map(function ($dateItems) {
                return abs($dateItems->sum('jumlah'));
            });
        });
        $kategoriTransferKeluar = $kategori_transfer_pengeluaran->groupBy('to_account_id')->map(function ($items) {
            return $items->groupBy(function ($item) {
                return $item->nama_akun;
            })->map(function ($nameAccounts) {
                return $nameAccounts->sum('jumlah');
            });
        });



        // Calculate cumulative values and saldo_akhir
        $akumulasi = $jumlah_pemasukan - abs($jumlah_pengeluaran);
        $saldo_akhir = $saldo_awal + $akumulasi;

        // Return response with calculated values
        return response()->json([
            'jumlah_pemasukan' => $jumlah_pemasukan,
            'jumlah_pengeluaran' => $jumlah_pengeluaran,
            'saldo_awal' => $saldo_awal,
            'saldo_akhir' => $saldo_akhir,
            'akumulasi' => $akumulasi,
            'kategori_pemasukan' => $pemasukan_per_kategori,
            'kategori_pengeluaran' => $pengeluaran_per_kategori,
            'kategori_pemasukan_tanggal' => $pemasukan_per_kategori_per_tanggal,
            'kategori_pengeluaran_tanggal' => $pengeluaran_per_kategori_per_tanggal,
            'nama_akun_kecil' => $nama_akun,
            'kategori_transfer_masuk' => $kategoriTransferMasuk,
            'kategori_transfer_keluar' => $kategoriTransferKeluar,
        ]);
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
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_akun' => 'required|string|max:255',
            'tampil' => 'required|in:y,t',
        ]);

        // Ambil nama_akun lama sebelum update
        $kas = akun_kas::findOrFail($id);
        $nama_akun_lama = $kas->nama_akun;

        // Update akun_kas
        $kas->nama_akun = $request->nama_akun;
        $kas->tampil = $request->tampil;
        $kas->save();

        // Jika update akun_kas berhasil
        if ($kas) {
            // Update kas_bank terkait (contoh: memperbarui data yang terkait dengan nama_akun)
            $updatedKasBank = kas_bank::where('nama_akun', $nama_akun_lama)
                ->update([
                    'nama_akun' => $request->nama_akun,
                    // Update kolom lain sesuai kebutuhan
                ]);

            if ($updatedKasBank) {
                return redirect()->back()->with('success', 'Buku Kas dan Kas Bank berhasil diperbarui.');
            } else {
                return redirect()->back()->with('error', 'Gagal memperbarui Kas Bank.');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal memperbarui Buku Kas.');
        }
    }

    public function updatePelanggan(Request $request, $id)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required',
        ]);

        $pelanggan = pelanggan::findOrFail($id);
        $pelanggan->nama_pelanggan = $request->nama_pelanggan;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->save();

        return redirect()->back()->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(akun_kas $id)
    {
        // Ambil nama_akun sebelum menghapus akun_kas
        $nama_akun_lama = $id->nama_akun;

        // Hapus akun_kas
        $kas = $id->delete();

        if ($kas) {
            // Hapus data terkait di kas_bank berdasarkan nama_akun
            $deleteKasBank = kas_bank::where('nama_akun', $nama_akun_lama)->delete();

            if ($deleteKasBank) {
                return redirect()->back()->with('success', 'Buku Kas dan Kas Bank berhasil dihapus.');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus Kas Bank.');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus Buku Kas.');
        }
    }

    public function destroyKasBank(akun_kas $id)
    {
        // Ambil nama_akun sebelum menghapus akun_kas
        $kasBankId = $id;
        dd($kasBankId);
        // Hapus akun_kas
        $kas = $id->delete();

        if ($kas) {
            // Hapus data terkait di kas_bank berdasarkan nama_akun
            $deleteKasBank = kas_bank::where('nama_akun', $nama_akun_lama)->delete();

            if ($deleteKasBank) {
                return redirect()->back()->with('success', 'Buku Kas dan Kas Bank berhasil dihapus.');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus Kas Bank.');
            }
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus Buku Kas.');
        }
    }

    public function destroyPelanggan(pelanggan $id)
    {
        $id->delete();
        return redirect()->back()->with('success', 'Data Pelangan deleted successfully');
    }
}
