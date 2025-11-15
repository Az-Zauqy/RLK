<?php

namespace App\Http\Controllers;

use App\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::all();
        return view('admin.master.size.index', compact('sizes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.master.size.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sizes',
        ], [
            'name.required' => 'Nama ukuran wajib di isi',
            'name.unique' => 'Nama ukuran sudah ada, ganti dengan nama lain',
        ]);
        try {
            Size::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-')
            ]);
            return redirect()->route('master.size.index')->with('message', 'Data berhasil disimpan');
        } catch (Exception $e) {
            Log::error("Size save error " . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return view('admin.master.size.edit', compact('size'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Nama ukuran wajib di isi',
        ]);
        try {
            $size = Size::findOrFail($id);
            $size->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-')
            ]);
            return redirect()->route('master.size.index')->with('message', 'Data berhasil di perbaharui');
        } catch (Exception $e) {
            Log::error("Size update error " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $size = Size::findOrFail($id);
            $size->delete();
            return redirect()->route('master.size.index')->with('message', 'Data berhasil dihapus');
        } catch (Exception $e) {
            Log::error("Size delete error " . $e->getMessage());
        }
    }
}
