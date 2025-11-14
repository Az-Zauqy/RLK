@extends('admin.partials._layout') @section('events', 'active')
@section('collapseEvents', 'show')
@section('allEvents', 'active')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-2 text-gray-800">Edit Event</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT') <div class="form-group">
                    <label><strong>Title (Judul <h4>) *</strong></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
                </div>
                
                <div class="form-group">
                    <label>Subtitle (Teks <p>)</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $event->subtitle) }}">
                </div>
                
                <div class="form-group">
                    <label>Link Tombol "Detail" (URL)</label>
                    <input type="url" name="link" class="form-control" placeholder="https://..." value="{{ old('link', $event->link) }}">
                </div>
                
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" class="form-control" required>
                        <option value="active" {{ $event->status == 'active' ? 'selected' : '' }}>Active (Tampilkan "Detail")</option>
                        <option value="coming_soon" {{ $event->status == 'coming_soon' ? 'selected' : '' }}>Segera Hadir (Tampilkan "Segera Hadir")</option>
                        <option value="inactive" {{ $event->status == 'inactive' ? 'selected' : '' }}>Inactive (Sembunyikan)</option>
                    </select>
                </div>
                
                <hr>
                <h5 class="text-gray-800">Opsional (Untuk Halaman Detail Event)</h5>
                
                <div class="form-group">
                    <label>Ganti Gambar Event (Untuk Halaman Detail)</label>
                    <input type="file" name="image" class="form-control">
                    @if($event->image)
                        <small class="d-block mt-2">Gambar saat ini:</small>
                        <img src="{{ asset('storage/' . $event->image) }}" alt="Gambar Event" height="100" class="img-thumbnail">
                    @endif
                </div>
                
                <div class="form-group">
                    <label>Deskripsi (Untuk Halaman Detail)</label>
                    <textarea name="description" class="form-control" rows="5">{{ old('description', $event->description) }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                    Batal
                </a>

            </form>
        </div>
    </div>

</div>
@endsection