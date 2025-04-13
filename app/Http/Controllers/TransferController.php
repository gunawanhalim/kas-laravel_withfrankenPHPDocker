<?php

namespace App\Http\Controllers;

use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use Illuminate\Http\Request;
use App\Models\TransferLog;

class TransferController extends Controller
{
    public function index()
    {   
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        $transfer = TransferLog::with('fromAccount','toAccount')->orderBy('created_at','asc')->paginate(25);

        return view('/transfer_logs.index',['transfer' => $transfer,'kas'=> $kas,'linkCategori' => $linkCategori]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids', []);
    
        if (count($ids) > 0) {
            // Lakukan penghapusan item dengan ID yang diterima dari permintaan AJAX
            TransferLog::whereIn('id', $ids)->delete();
            
            // Berhasil menghapus item
            return response()->json(['succes' => false]);
        } else {
            // Tidak ada ID yang diterima, tangani kesalahan jika diperlukan
            return response()->json(['error' => true, 'message' => 'Tidak ada ID yang diterima.']);
        }
    }
}
