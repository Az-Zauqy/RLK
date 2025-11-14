@extends('admin.partials._layout')
@section('title','Form Blog')
@section('collapseBlog','show')
@section('addblog','active')

@section('css')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
  <link href="{{ asset('css/blog-admin.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid">
  <h1 class="h5 mb-4 text-gray-800">
    {{ isset($blog) ? 'Form Edit Blog' : 'Form Tambah Blog' }}
  </h1>

  <div class="card shadow-sm">
    <div class="card-body">

      {{-- === FORM === --}}
      @if(isset($blog))
        <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
      @else
        <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
      @endif

        {{-- Judul --}}
        <div class="form-group mb-4">
          <label>Judul Blog</label>
          <input 
            type="text" 
            name="title" 
            class="form-control" 
            placeholder="Masukkan judul blog" 
            value="{{ old('title', $blog->title ?? '') }}">
        </div>

        {{-- Isi --}}
        <div class="form-group mb-4">
          <label>Isi Blog</label>
          <textarea 
            id="page" 
            name="body" 
            class="form-control">{{ old('body', $blog->body ?? '') }}</textarea>
        </div>

        {{-- 3 Kolom: Kategori, Status, Tags --}}
        <div class="row mb-4">
          <div class="col-md-4">
            <div class="form-group">
              <label>Kategori</label>
              <select name="kategori_id" class="form-control">
                <option value="">-- Pilih Kategori --</option>
                @foreach($cats as $id => $name)
                  <option value="{{ $id }}" {{ old('kategori_id', $blog->kategori_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="draft" {{ old('status', $blog->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $blog->status ?? '') == 'published' ? 'selected' : '' }}>Publish</option>
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Tags</label>
              <select id="selTag" name="tagger[]" multiple="multiple" class="form-control"></select>
            </div>
          </div>
        </div>

        {{-- Upload Gambar --}}
        <div class="form-group">
          <label>Gambar Blog</label>
          <div id="preview-container" class="preview-container">
            @if(isset($images) && $images->count() > 0)
              @foreach($images as $img)
                <div class="preview-item {{ isset($blog->image) && $blog->image === $img->filename ? 'cover' : '' }}">
                  <img src="{{ asset('uploads/blogs/'.$img->filename) }}" alt="preview">
                  <div class="preview-actions">
                    <button type="button" 
                            class="btn-preview btn-edit" 
                            data-id="{{ $img->id }}" 
                            title="Ubah Gambar">
                      <i class="fas fa-pen"></i>
                    </button>
                    <button type="button" class="btn-preview btn-cover" 
                            data-id="{{ $img->id }}" 
                            data-blog="{{ $blog->id ?? '' }}" 
                            title="Jadikan Cover"><i class="fas fa-star"></i></button>
                    <button type="button" class="btn-preview btn-delete" 
                            data-id="{{ $img->id }}" 
                            title="Hapus"><i class="fas fa-trash"></i></button>
                  </div>
                </div>
              @endforeach
            @endif
          </div>

          <label for="images" class="btn btn-secondary w-100 mt-2">
            <i class="fas fa-folder-open"></i> Tambah Gambar
          </label>
          <input type="file" id="images" name="fotoblog[]" class="d-none" multiple accept="image/*">
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-between mt-4">
          <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-save"></i> {{ isset($blog) ? 'Update' : 'Simpan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {

    // === SUMMERNOTE ===
    $('#page').summernote({
      placeholder: 'Tulis konten blog...',
      height: 250,
      toolbar: [['style', ['bold', 'italic', 'underline']], ['para', ['ul', 'ol']]]
    });

    // === SELECT2 TAGS ===
    $('#selTag').select2({
      tags: true,
      tokenSeparators: [","],
      ajax: {
        url: "{{ route('admin.blogs.tagpost') }}",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: params => ({ _token: '{{ csrf_token() }}', search: params.term }),
        processResults: data => ({ results: data })
      }
    });

    // === PREVIEW GAMBAR ===
    const input = document.getElementById('images');
    const container = document.getElementById('preview-container');
    const csrf = '{{ csrf_token() }}';

    // --- styling gambar biar rapi ---
    function setImageStyle(img) {
      img.style.width = "120px";
      img.style.height = "120px";
      img.style.objectFit = "cover";
      img.style.borderRadius = "8px";
      img.style.border = "2px solid transparent";
      img.style.transition = "0.2s";
    }

    // --- fungsi bantu tombol ---
    function attachImageEvents(div) {
      const btnDelete = div.querySelector('.btn-delete');
      const btnCover = div.querySelector('.btn-cover');
      const btnEdit = div.querySelector('.btn-edit');
      const img = div.querySelector('img');
      const isEditMode = btnCover?.dataset.blog && btnCover?.dataset.id; // hanya true kalau sedang edit blog

      // === HAPUS GAMBAR LAMA ===
      if (btnDelete && btnDelete.dataset.id) {
        btnDelete.addEventListener('click', () => {
          if (!confirm('Hapus gambar ini?')) return;
          const id = btnDelete.dataset.id;
          fetch(`/admin/blogs/image/${id}/delete`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) div.remove();
            else alert('Gagal menghapus gambar.');
          })
          .catch(() => alert('Terjadi kesalahan.'));
        });
      } else if (btnDelete) {
        // Untuk gambar baru (belum disimpan)
        btnDelete.addEventListener('click', () => {
          div.remove();
        });
      }

      // === EDIT GAMBAR ===
      btnEdit?.addEventListener('click', () => {
        const tempInput = document.createElement('input');
        tempInput.type = 'file';
        tempInput.accept = 'image/*';
        tempInput.onchange = e => {
          const file = e.target.files[0];
          const reader = new FileReader();
          reader.onload = ev => img.src = ev.target.result;
          reader.readAsDataURL(file);

          // kalau edit mode → update ke server juga
          if (isEditMode) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', csrf);
            fetch(`/admin/blogs/image/${btnEdit.dataset.id}/replace`, {
              method: 'POST',
              body: formData
            })
            .then(res => res.json())
            .then(data => {
              if (!data.success) alert('Gagal mengganti gambar di server.');
            })
            .catch(() => alert('Terjadi kesalahan.'));
          }
        };
        tempInput.click();
      });

      // === JADIKAN COVER ===
      btnCover?.addEventListener('click', () => {
        // kalau mode edit → kirim ke server
        if (isEditMode) {
          const imgId = btnCover.dataset.id;
          const blogId = btnCover.dataset.blog;
          if (!confirm('Jadikan gambar ini sebagai cover utama?')) return;

          fetch(`/admin/blogs/image/${imgId}/set-cover/${blogId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              document.querySelectorAll('.preview-item').forEach(item => {
                item.classList.remove('cover');
                const icon = item.querySelector('.btn-cover i');
                if (icon) icon.style.color = '#fff';
              });
              div.classList.add('cover');
              const icon = btnCover.querySelector('i');
              if (icon) icon.style.color = 'gold';
            } else {
              alert('Gagal menjadikan cover.');
            }
          })
          .catch(() => alert('Terjadi kesalahan.'));
        } 
        // kalau tambah blog → cover hanya di-preview
        else {
          document.querySelectorAll('.preview-item').forEach(item => {
            item.classList.remove('cover');
            const icon = item.querySelector('.btn-cover i');
            if (icon) icon.style.color = '#fff';
          });
          div.classList.add('cover');
          const icon = btnCover.querySelector('i');
          if (icon) icon.style.color = 'gold';
        }
      });
    }

    // --- buat nambah preview baru ---
    function addPreview(src) {
      const div = document.createElement('div');
      div.classList.add('preview-item');
      div.innerHTML = `
        <img src="${src}" alt="preview">
        <div class="preview-actions">
          <button type="button" class="btn-preview btn-edit" title="Edit"><i class="fas fa-pen"></i></button>
          <button type="button" class="btn-preview btn-cover" title="Jadikan Cover"><i class="fas fa-star"></i></button>
          <button type="button" class="btn-preview btn-delete" title="Hapus"><i class="fas fa-trash"></i></button>
        </div>
      `;
      const img = div.querySelector('img');
      setImageStyle(img);
      attachImageEvents(div);
      container.appendChild(div);
    }

    if (input && container) {
      input.addEventListener('change', e => {
        const files = e.target.files;
        if (!files.length) return;

        Array.from(files).forEach(file => {
          const reader = new FileReader();
          reader.onload = ev => {
            const div = document.createElement('div');
            div.classList.add('preview-item');
            div.innerHTML = `
              <img src="${ev.target.result}" alt="preview">
              <div class="preview-actions">
                <button type="button" class="btn-preview btn-edit" title="Edit"><i class="fas fa-pen"></i></button>
                <button type="button" class="btn-preview btn-cover" title="Jadikan Cover"><i class="fas fa-star"></i></button>
                <button type="button" class="btn-preview btn-delete-temp" title="Hapus"><i class="fas fa-trash"></i></button>
              </div>
            `;
            const img = div.querySelector('img');
            setImageStyle(img);
            container.appendChild(div);

            // aktifin tombol di gambar baru
            attachImageEvents(div);
          };
          reader.readAsDataURL(file);
        });
      });
    }

    // --- hapus gambar baru (belum upload) ---
    document.addEventListener('click', e => {
      if (e.target.closest('.btn-delete-temp')) {
        e.target.closest('.preview-item').remove();
      }
    });

    // --- pastikan gambar lama kecil + tombol aktif ---
    document.querySelectorAll("#preview-container .preview-item").forEach(div => {
      const img = div.querySelector("img");
      if (img) setImageStyle(img);
      attachImageEvents(div);
    });

    // === efek visual kalau gambar dijadikan cover ===
    const style = document.createElement('style');
    style.innerHTML = `
      .preview-item.cover img {
        border: 2px solid gold !important;
        box-shadow: 0 0 6px rgba(255,215,0,0.6);
      }
      .preview-item.cover .btn-cover {
        color: gold;
      }
    `;
    document.head.appendChild(style);

  });
  </script>
@endsection
