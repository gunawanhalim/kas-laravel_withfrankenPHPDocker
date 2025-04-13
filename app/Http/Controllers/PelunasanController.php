<?php

namespace App\Http\Controllers;

use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use App\Models\piutang;
use App\Models\UtangModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PelunasanController extends Controller
{
    public function indexPiutang(Request $request)
    {
        $kas = akun_kas::paginate(25);
        $kas_bank = kas_bank::paginate(50);
        $linkCategori = CategorieSupplierModel::all();
        // Query dasar untuk piutang dengan relasi ke kas_bank
            // Query dasar untuk piutang dengan relasi ke kas_bank
            $query = piutang::with('kas_bank')
            ->selectRaw('
                nomor_bukti,
                SUM(jumlah) as total_jumlah,
                MAX(tanggal_bukti) as tanggal_bukti,
                MAX(nomor_nota) as nomor_nota,
                MAX(nama_pelanggan) as nama_pelanggan,
                MAX(kategori) as kategori,
                MAX(nama_akun) as nama_akun,
                COALESCE(MAX(jatuh_tempo), \'No Date Available\') as jatuh_tempo
            ')
            ->groupBy('nomor_bukti')
            ->havingRaw('SUM(CASE WHEN kategori = "utang" THEN -jumlah ELSE jumlah END) = 0');
    
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
            $query->orderBy('nomor_bukti', 'asc');
        }
    
        $pelunasan = $query->paginate(25);
    
        return view('pelunasan.piutang', compact('kas', 'kas_bank', 'pelunasan','linkCategori'));
    }
    public function indexUtang(Request $request)
    {
        $kas = akun_kas::paginate(25);
        $kas_bank = kas_bank::paginate(50);
        $linkCategori = CategorieSupplierModel::all();
        // Query dasar untuk piutang dengan relasi ke kas_bank
            // Query dasar untuk piutang dengan relasi ke kas_bank
            $query = UtangModel::with('kas_bank')
            ->selectRaw('
                nomor_bukti,
                SUM(CASE WHEN kategori = "utang" THEN -jumlah ELSE jumlah END) as total_jumlah,
                MAX(tanggal_bukti) as tanggal_bukti,
                MAX(nomor_nota) as nomor_nota,
                MAX(kategori) as kategori,
                MAX(nama_akun) as nama_akun,
                MAX(jatuh_tempo) as jatuh_tempo
            ')
            ->groupBy('nomor_bukti')
            ->havingRaw('SUM(CASE WHEN kategori = "utang" THEN -jumlah ELSE jumlah END) = 0');
        
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
            $query->orderBy('nomor_bukti', 'asc'); // Default sorting
        }
    
        // Ambil data dengan paginasi
        $pelunasan = $query->paginate(25);
    
        return view('pelunasan.utang', compact('kas', 'kas_bank', 'pelunasan','linkCategori'));
    }
}
