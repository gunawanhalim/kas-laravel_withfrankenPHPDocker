<?php

namespace App\Http\Controllers;

use PDF;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KasBankExport;
use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use App\Models\penjualan;
use App\Models\piutang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class Controller

{
    public function exportPDF(Request $request)
    {
        // Ambil parameter pencarian dan tanggal dari request
        $linkCategori = CategorieSupplierModel::all();
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query data kas_bank dengan filter pencarian dan tanggal
        $query = DB::table('kas_bank')
            ->select('tanggal_bukti', 'nama_akun', 'kategori', 'subcategories_id', DB::raw('SUM(jumlah) as total_jumlah'))
            ->groupBy('tanggal_bukti', 'nama_akun', 'kategori', 'subcategories_id')
            ->orderBy('nama_akun');

        // Terapkan filter pencarian jika ada
        if ($search) {
            $query->where('nama_akun', 'like', '%' . $search . '%');
        }

        // Terapkan filter tanggal jika ada
        if ($startDate) {
            $query->whereDate('tanggal_bukti', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_bukti', '<=', $endDate);
        }

        // Eksekusi query
        $kasBankData = $query->get();

        $penjualan = penjualan::all();
        $piutang = piutang::all();
        $pdf = PDF::loadView('exports.pdf', ['kas_bank' => $kasBankData, 'penjualan' => $penjualan, 'piutang' => $piutang, 'linkCategori' => $linkCategori]);

        // Menyajikan PDF untuk ditampilkan di browser
        return $pdf->stream('kas_bank_data.pdf');
    }
    public function chartPDF(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'search' => 'nullable|string',
        ]);

        // Ambil data dari model berdasarkan filter tanggal dan pencarian
        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $search = $request->search;

        $query = kas_bank::whereBetween('tanggal_bukti', [$startDate, $endDate]);
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        if ($search) {
            $query->where('nama_akun', 'like', '%' . $search . '%');
        }

        $data = $query->get();

        // Generate PDF menggunakan Dompdf
        $pdf = PDF::loadView('chartPDF', ['data' => $data, 'kas' => $kas, 'linkCategori' => $linkCategori]);

        // Download atau tampilkan PDF
        // return $pdf->download('chart.pdf');
        return $pdf->stream('kas_bank_data.pdf');
    }



    public function exportDOC()
    {
        // Inisialisasi objek PhpWord
        $phpWord = new PhpWord();

        // Buat section baru
        $section = $phpWord->addSection();

        // Tambahkan teks ke section
        $section->addText('Data DOC', array('size' => 16, 'bold' => true));
        $section->addText('Ini adalah tampilan DOC yang sederhana.');

        // Simpan dokumen ke file
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('file.docx');

        // Kembalikan response, jika diperlukan

    }



    public function exportExcel()
    {
        return Excel::download(new KasBankExport(), 'DataKas.xlsx');
    }

    // Metode untuk mengunduh PDF dengan filter
    public function downloadPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchData = $request->input('searchData');

        // Membuat ekspor data dengan filter yang diberikan
        return Excel::download(new KasBankExport($startDate, $endDate, $searchData), 'dataRincian.pdf');
    }

    public function eInvoice()
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('/invoice.index', compact('kas', 'linkCategori', 'linkCategori'));
    }

    public function kasPDF(Request $request)
    {
        $data = $this->getData($request);
        $pdf = PDF::loadView('exports.kasPDF', compact('data'));
        return $pdf->stream('report-kas.pdf');
    }

    public function kasExcel(Request $request)
    {
        $data = $this->getData($request);
        return Excel::download(new ExportExcel($data), 'report.xlsx');
    }

    public function kasDoc(Request $request)
    {
        $data = $this->getData($request);
        $headers = array(
            "Content-Type" => "application/vnd.ms-word",
        );
        return response()->download('exports.doc', 'report.doc', $headers);
    }

    private function getData($request)
    {
        // Retrieve data based on search parameters
        $query = kas_bank::query();

        // Apply filters (if any)
        if ($request->has('search')) {
            $query->where('nama_akun', 'like', '%' . $request->search . '%')
                ->orWhere('kategori', 'like', '%' . $request->search . '%')
                ->orWhere('keterangan', 'like', '%' . $request->search . '%')
                ->orWhere('nama_user', 'like', '%' . $request->search . '%')
            ;
        }
        // Add more filters as per your needs

        return $query->get();
    }

    public function invoice()
    {
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('eInvoice.formInvoice', compact('kas', 'linkCategori'));
    }

    public function invoicePDF(Request $request)
    {
        $lastNomorBukti = piutang::orderBy('nomor_bukti', 'desc')->first();
        $lastNumber = $lastNomorBukti ? (int) substr($lastNomorBukti->nomor_bukti, -6) : 0;
        $newNumber = $lastNumber + 1;
        $newNomorBukti = 'BKM.' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
        // dd($request->all());
        // if ($request->invpaidstamp == "1") {

        //     $penjualan = penjualan::create([
        //         'nomor_nota' => $request->invnumb,
        //         'tanggal_nota' => $request->invdate,
        //         'jatuh_tempo' => $request->invduedate,
        //         'nama_pelanggan' => $request->costname,
        //         'alamat' => $request->costaddress1,
        //         'total' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $piutang = piutang::create([
        //         'nomor_bukti' => $newNomorBukti,
        //         'tanggal_bukti' => $request->invdate,
        //         'nomor_nota' => $request->invnumb,
        //         'jatuh_tempo' => $request->invduedate,
        //         'kategori' => $request->kategori,
        //         'nama_akun' => $request->nama_akun,
        //         'nama_pelanggan' => $request->costname,
        //         'jumlah' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $kas_piutang = kas_bank::create([
        //         'tanggal_bukti' => $request->invdate,
        //         'nama_akun' => $piutang->nama_akun,
        //         'from' => "Piutang",
        //         'nomor_bukti' => $piutang->nomor_bukti,
        //         'subcategories_id' => 2,
        //         'kategori' => $request->kategori,
        //         'jumlah' => $request->invtotal,
        //         'nama_pelanggan' => $request->costname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $pelunasan = piutang::create([
        //         'nomor_bukti' => $piutang->nomor_bukti,
        //         'tanggal_bukti' => $request->invdate,
        //         'nomor_nota' => $request->invnumb,
        //         'jatuh_tempo' => $request->invduedate,
        //         'kategori' => $request->kategori,
        //         'nama_akun' => $request->nama_akun,
        //         'nama_pelanggan' => $request->costname,
        //         'jumlah' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        // } elseif ($request->invpastduestamp == "1") {
        //     $penjualan = penjualan::create([
        //         'nomor_nota' => $request->invnumb,
        //         'tanggal_nota' => $request->invdate,
        //         'jatuh_tempo' => $request->invduedate,
        //         'nama_pelanggan' => $request->costname,
        //         'alamat' => $request->costaddress1,
        //         'total' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $piutang = piutang::create([
        //         'nomor_bukti' => $newNomorBukti,
        //         'tanggal_bukti' => $request->invdate,
        //         'nomor_nota' => $request->invnumb,
        //         'jatuh_tempo' => $request->invduedate,
        //         'kategori' => $request->kategori,
        //         'nama_akun' => $request->nama_akun,
        //         'nama_pelanggan' => $request->costname,
        //         'jumlah' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        // } elseif ($request->invcreditcheck == "1") {
        //     $penjualan = penjualan::create([
        //         'nomor_nota' => $request->invnumb,
        //         'tanggal_nota' => $request->invdate,
        //         'jatuh_tempo' => $request->invduedate,
        //         'nama_pelanggan' => $request->costname,
        //         'alamat' => $request->costaddress1,
        //         'total' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $piutang = piutang::create([
        //         'nomor_bukti' => $newNomorBukti,
        //         'tanggal_bukti' => $request->invdate,
        //         'nomor_nota' => $request->invnumb,
        //         'jatuh_tempo' => $request->invduedate,
        //         'kategori' => $request->kategori,
        //         'nama_akun' => $request->nama_akun,
        //         'nama_pelanggan' => $request->costname,
        //         'jumlah' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $kas_piutang = kas_bank::create([
        //         'tanggal_bukti' => $request->invdate,
        //         'nama_akun' => $piutang->nama_akun,
        //         'from' => "Piutang",
        //         'nomor_bukti' => $piutang->nomor_bukti,
        //         'subcategories_id' => 2,
        //         'kategori' => $request->kategori,
        //         'jumlah' => $request->invtotal,
        //         'nama_pelanggan' => $request->costname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $sudahdibayar = piutang::create([
        //         'nomor_bukti' => $piutang->nomor_bukti,
        //         'tanggal_bukti' => $request->invdate,
        //         'nomor_nota' => $request->invnumb,
        //         'jatuh_tempo' => $request->invduedate,
        //         'kategori' => $request->kategori,
        //         'nama_akun' => $request->nama_akun,
        //         'nama_pelanggan' => $request->costname,
        //         'jumlah' => $request->invcredit,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        // } else {
        //     $penjualan = penjualan::create([
        //         'nomor_nota' => $request->invnumb,
        //         'tanggal_nota' => $request->invdate,
        //         'jatuh_tempo' => $request->invduedate,
        //         'nama_pelanggan' => $request->costname,
        //         'alamat' => $request->costaddress1,
        //         'total' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        //     $piutang = piutang::create([
        //         'nomor_bukti' => $newNomorBukti,
        //         'tanggal_bukti' => $request->invdate,
        //         'nomor_nota' => $request->invnumb,
        //         'jatuh_tempo' => $request->invduedate,
        //         'kategori' => $request->kategori,
        //         'nama_akun' => $request->nama_akun,
        //         'nama_pelanggan' => $request->costname,
        //         'jumlah' => $request->invtotal,
        //         'nama_sales' => $request->shipname,
        //         'nama_user' => Auth::user()->name,
        //         'tanggal_log' => Carbon::now(),
        //     ]);
        // }

        $invsubtotal = (float) $request->input('invsubtotal');
        $invtotal =  is_numeric($request->input('invsubtotal')) ? (float) $request->input('invsubtotal') : 0;

        // Ambil semua data dari request
        $data = $request->all();
        $details = [];
        foreach ($data['invid'] as $index => $id) {
            $details[] = [
                'invid' => $data['invid'][$index],
                'invdesc' => $data['invdesc'][$index],
                'invqty' => $data['invqty'][$index],
                'unitprice' => $data['unitprice'][$index],
                'linetotal' => $data['linetotal'][$index],
            ];
        }
        // Tambahkan invdesc yang sudah digabungkan ke dalam $data
        $data['invsubtotal'] = $invsubtotal;
        $data['invtotal'] = $invtotal;
        // $data['invid'] = $invid;
        // $data['invqty'] = $invqty;
        // $data['unitprice'] = $unitprice;
        // $data['linetotal'] = $linetotal;
        // $data['invdesc'] = (string) $data['invdesc'];
        // dd($request->all());
        $pdf = PDF::loadView('eInvoice.eInvoice', compact('data', 'details'));


        // Gabungkan nilai invdesc menjadi satu string dipisahkan koma dan spasi
        // $invdesc = implode(', ', $request->input('invdesc'));
        // $invid = implode(', ', $request->input('invid'));
        // $invqty = implode(', ', $request->input('invqty'));
        // $unitprice = implode(', ', $request->input('unitprice'));

        // Stream atau download PDF
        return $pdf->stream('report-kas.pdf');
    }
}
