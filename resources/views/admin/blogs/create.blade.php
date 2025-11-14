@extends('admin.partials._layout')
@section('title','Create posts')
@section('collapseBlog','show')
@section('addblog','active')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
.preview-container img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 5px;
    margin: 5px;
}
</style>
@endsection

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h5 mb-4 text-gray-800">Tambah <small>Blog</small></h1>
    {{ Form::open(['route' => 'admin.blogs.store', 'files' => true]) }}
        @include('admin.blogs.form')
    {{ Form::close() }}
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$('#page').summernote({
    placeholder: 'Tulis konten blog...',
    height: 250,
    toolbar: [['style', ['bold', 'italic', 'underline']], ['para', ['ul', 'ol']]],
});

// Select2 Tags
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

// Preview Multi Image
$('#input-foto-blog').on('change', function(e) {
    const files = e.target.files;
    $('#preview-container').html('');
    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => $('#preview-container').append(`<img src="${e.target.result}" />`);
        reader.readAsDataURL(file);
    });
});
</script>

{{-- <script type="text/javascript">
        $('#page').summernote({
            placeholder: 'Tulis content page...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
            ],
            height: 250
        });
        //preview foto blog
        $(document).ready(function () {
          'use strict';

        //foto1
        var previewFotoblog = $('#foto-blog'),
            fotoblog = $('#input-foto-blog');

        //preview 1
        if (fotoblog) {
            fotoblog.on('change', function (e) {
                var reader = new FileReader(),
                files = e.target.files;
                var fsize = files[0].size;
                if(fsize > 2000000) {
                    alert("Ukuran foto terlalu besar, maximal 2mb");
                  } else {
                    reader.onload = function () {
                      if (previewFotoblog) {
                          previewFotoblog.attr('src', reader.result);
                      }
                  };
                    reader.readAsDataURL(files[0]);
                }
                  });
            } 
      });
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function(){

      $( "#selTag" ).select2({
        tags: true,
        tokenSeparators: [","],
        ajax: { 
          url: "{{route('admin.blogs.tagpost')}}",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              _token: CSRF_TOKEN,
              search: params.term // search term
            };
          },
          processResults: function (response) {
            return {
              results: response
            };
          },
          cache: true
        }

      });

    });
    </script>
--}} 
@endsection