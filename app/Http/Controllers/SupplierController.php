<?php

namespace App\Http\Controllers;

use App\Models\CategorieSupplierModel;
use App\Models\kas_bank;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categori_suppliers', 
        ],
        [
            'name.required' => 'Nama kategori wajib di isi',
            'name.unique' => 'Nama kategori telah ada',
            // 'name.unique' => 'Nama kategori sudah ada',
        ],
        );
        CategorieSupplierModel::create($request->all());
        return redirect()->back()->with('success', 'Kategori Supplier telah disimpan.');  
    }

    public function update(Request $request, $id)
    {
        $item = CategorieSupplierModel::findOrFail($id);
        $oldName = $item->name; 
        $item->name = $request->input('name');
        $kas_bank = kas_bank::where('kategori', $oldName)->update(['kategori' => $item->name]);
        $item->save();

        return response()->json(['message' => 'Kategori Supplier berhasil diperbarui', 'kas_bank' => $kas_bank]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subcategory = CategorieSupplierModel::findOrFail($id);

        // Hapus subkategori
        $subcategory->delete();

        // Kembalikan respons
        return response()->json(['message' => 'Kategori Supplier berhasil dihapus'], 200);
    }

    public function moveSubcategories(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'name' => 'required|string', // Menyatakan bahwa 'name' harus ada dan bertipe string
        ]);
    
        // Temukan subkategori berdasarkan ID yang diberikan
        $subcategory = CategorieSupplierModel::findOrFail($id);
        $oldName = $subcategory->name; // Simpan nama lama sebelum diperbarui
    
        // Lakukan pembaruan kategori
        $subcategory->name = $request->name;
        $subcategory->save();
    
        // Jika pembaruan berhasil, hapus semua subkategori yang terkait dan pindahkan kolom yang terforeign
        if ($subcategory->wasChanged('name')) {
            // Perbarui semua entri di kas_bank yang terhubung dengan subkategori yang dipindahkan
            kas_bank::where('kategori', $oldName)->update(['kategori' => $request->name]);
    
            // Hapus subkategori lama
            CategorieSupplierModel::where('id', $id)->delete();
        }
    
        // Kembalikan respons
        return response()->json(['message' => 'Kategori berhasil diperbarui'], 200);
    }
}
