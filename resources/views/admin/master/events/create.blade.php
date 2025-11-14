@extends('admin.partials._layout')

@section('events', 'active')
@section('collapseEvents', 'show')
@section('addEvent', 'active')

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Tambah Event Baru</h1>

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Konten Event</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-info small">Data (Title, Subtitle, Link) akan tampil di overlay homepage jika event ini 'Active' dan merupakan yang terbaru.</p>
                        
                        <div class="form-group">
                            <label><strong>Title (Judul <h4>) *</strong></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Subtitle (Teks <p>)</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                        </div>
                        
                        <div class="form-group">
                            <label>Link Tombol "Detail" (URL)</label>
                            <input type="url" name="link" class="form-control" placeholder="https://..." value="{{ old('link') }}">
                        </div>
                        
                        <hr>
                        <h5 class="text-gray-800">Opsional (Untuk Halaman Detail Event)</h5>
                        
                        <div class="form-group">
                            <label>Deskripsi (Untuk Halaman Detail)</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div> <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Publikasi & Gambar</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="active" selected>Active (Tampilkan "Detail")</option>
                                <option value="coming_soon">Segera Hadir (Tampilkan "Segera Hadir")</option>
                                <option value="inactive">Inactive (Sembunyikan)</option>
                            </select>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label>Gambar Event (Untuk Halaman Detail)</label>
                            <input type="file" name="image" id="imageInput" class="form-control">
                            <img id="imagePreview" src="#" alt="Preview Gambar" class="img-thumbnail mt-2" style="display:none; max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div> </div> <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Event
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </form> </div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(event) {
                        imagePreview.src = event.target.result;
                        imagePreview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(e.target.files[0]);
                } else {
                    imagePreview.src = '#';
                    imagePreview.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection