<?php

namespace App\Http\Controllers;

use App\Event; // Pastikan ini 'App\Event', sesuai struktur Laravel 6
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Penting untuk upload gambar

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::latest()->get();
        // DIPERBAIKI: Path view diubah ke 'admin.master.events'
        return view('admin.master.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // DIPERBAIKI: Path view diubah ke 'admin.master.events'
        return view('admin.master.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'status' => 'required|in:active,coming_soon,inactive',
        ]);

        $data = $request->all();

        // Cek jika ada file gambar yang di-upload
        if ($request->hasFile('image')) {
            // Simpan gambar di 'storage/app/public/events'
            $path = $request->file('image')->store('events', 'public');
            $data['image'] = $path;
        }

        // Buat data baru di database
        Event::create($data);

        // Arahkan kembali ke halaman index (nama route tidak berubah)
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        // Fungsi ini tidak kita gunakan di admin, jadi bisa dikosongi
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        // DIPERBAIKI: Path view diubah ke 'admin.master.events'
        return view('admin.master.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Boleh kosong saat update
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'status' => 'required',
        ]);

        $data = $request->all();

        // Cek jika ada gambar baru yang di-upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama (jika ada)
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            
            // Simpan gambar baru
            $path = $request->file('image')->store('events', 'public');
            $data['image'] = $path;
        }

        // Update data di database
        $event->update($data);

        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        // Hapus gambar dari storage (jika ada)
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        // Hapus data dari database
        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus.');
    }
}