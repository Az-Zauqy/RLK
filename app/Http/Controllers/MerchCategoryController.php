<?php

namespace App\Http\Controllers;

use App\MerchCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MerchCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MerchCategory::all();
        return view('admin.master.merchcategory.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.master.merchcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:merch_categories,name',
        ], [
            'name.required' => 'Nama kategori wajib di isi',
            'name.unique' => 'Nama kategori sudah ada, ganti dengan nama lain',
        ]);

        try {
            MerchCategory::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-')
            ]);
            return redirect()->route('master.merchcategory.index')->with('message', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            Log::error("MerchCategory save error " . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = MerchCategory::findOrFail($id);
        return view('admin.master.merchcategory.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:merch_categories,name,' . $id,
        ], [
            'name.required' => 'Nama kategori wajib di isi',
            'name.unique' => 'Nama kategori sudah ada, ganti dengan nama lain',
        ]);

        try {
            $category = MerchCategory::findOrFail($id);
            $category->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-')
            ]);
            return redirect()->route('master.merchcategory.index')->with('message', 'Data berhasil di perbaharui');
        } catch (\Exception $e) {
            Log::error("MerchCategory update error " . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat memperbarui data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $category = MerchCategory::findOrFail($id);
            $category->delete();
            return redirect()->route('master.merchcategory.index')->with('message', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("MerchCategory delete error " . $e->getMessage());
            return back()->withErrors('Terjadi kesalahan saat menghapus data');
        }
    }
}
