<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;
use App\Models\akun_kas;
use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use App\Models\subcategories;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = subcategories::with('kategori')->get();
        $kategori = categories::all();
        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();

        return view('categories.index',compact('kategori','kas','subcategories','linkCategori'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function searchKategoriPengeluaran(Request $request)
    {
        $search = $request->input('q');
    
        $results = subcategories::where('name', 'LIKE', "%{$search}%")
                                ->where('kategori_id',2)
                                ->select('kategori_id', 'name')
                                ->get();
    
        return response()->json($results);
    }

    public function searchKategoriPemasukan(Request $request)
    {
        $search = $request->input('q');
    
        $results = subcategories::where('name', 'LIKE', "%{$search}%")
                                ->where('kategori_id',1)
                                ->select('kategori_id', 'name')
                                ->get();
    
        return response()->json($results);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:subcategories', 
        ],
        [
            'name.required' => 'Nama kategori wajib di isi',
            'name.unique' => 'Nama kategori telah ada',
            // 'name.unique' => 'Nama kategori sudah ada',
        ],
        );
        subcategories::create($request->all());
        return redirect()->back()->with('success', 'Data telah disimpan.');  
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $pemasukan = subcategories::with('kategori')
                        ->where('kategori_id', 1) 
                        ->get();
        $pengeluaran = subcategories::with('kategori')
                        ->where('kategori_id', 2) 
                        ->get();
        $subcategories = subcategories::with('kategori')
                        ->get();
        
        $kategori = categories::all();

        $pemasukanSupplier = CategorieSupplierModel::where('kategori', "Supplier") 
                ->get();
        $pengeluaranSupplier = CategorieSupplierModel::where('kategori', "Piutang") 
                        ->get();
        $categoriSupplier = CategorieSupplierModel::all();
        
        $kategori = categories::all();

        $kas = akun_kas::all();
        $linkCategori = CategorieSupplierModel::all();
        

        return view('categories.show', compact(
            'kas',
            'kategori',
            'pemasukan',
            'pengeluaran',
            'linkCategori',
            'subcategories',
            'categoriSupplier',
            'pemasukanSupplier',
            'pengeluaranSupplier'
        ));
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
        $item = subcategories::findOrFail($id);
        $oldName = $item->name; 
        $item->name = $request->input('name');
        $kas_bank = kas_bank::where('kategori', $oldName)->update(['kategori' => $item->name]);
        $item->save();

        return response()->json(['message' => 'Item berhasil diperbarui', 'kas_bank' => $kas_bank]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subcategory = subcategories::findOrFail($id);

        // Hapus subkategori
        $subcategory->delete();

        // Kembalikan respons
        return response()->json(['message' => 'Subkategori berhasil dihapus'], 200);
    }

    public function moveSubcategories(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'name' => 'required|string', // Menyatakan bahwa 'name' harus ada dan bertipe string
        ]);
    
        // Temukan subkategori berdasarkan ID yang diberikan
        $subcategory = subcategories::findOrFail($id);
        $oldName = $subcategory->name; // Simpan nama lama sebelum diperbarui
    
        // Lakukan pembaruan kategori
        $subcategory->name = $request->name;
        $subcategory->save();
    
        // Jika pembaruan berhasil, hapus semua subkategori yang terkait dan pindahkan kolom yang terforeign
        if ($subcategory->wasChanged('name')) {
            // Perbarui semua entri di kas_bank yang terhubung dengan subkategori yang dipindahkan
            kas_bank::where('kategori', $oldName)->update(['kategori' => $request->name]);
    
            // Hapus subkategori lama
            subcategories::where('id', $id)->delete();
        }
    
        // Kembalikan respons
        return response()->json(['message' => 'Kategori berhasil diperbarui'], 200);
    }
}
