@extends('web.partials.layout')

@section('css')
<link rel="stylesheet" href="{{ asset('css/blog/blog-detail.css') }}">
@endsection

@section('content')
<section class="py-3">
  <div class="container blog-container">

    {{-- Breadcrumb 
    <nav arial-label="breadcrumb">
      <ol class="breadcrumb justify-content-left">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/blogs') }}">Blog</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
      </ol>
    </nav> --}}

    {{-- Judul --}}
    <h1 class="blog-title">{{ $blog->title }}</h1>

    {{-- Metadata tanggal & waktu --}}
    <div class="blog-meta">
      <i class="bi bi-calendar-event"></i>
      {{ $blog->created_at->translatedFormat('l, d F Y H:i:s') }}
    </div>

    {{-- Gambar utama --}}
    @if(!empty($blog->image))
    <div class="blog-image">
      <img src="{{ asset('uploads/blogs/'.$blog->image) }}" alt="{{ $blog->title }}">
    </div>
    @endif

    @php
        $bodyParts = preg_split('/(<\/p>)/i', $blog->body);
        $images = $blog->images->where('filename', '!=', $blog->image)->values();
        $imgIndex = 0;
    @endphp

    <div class="blog-body">
      @foreach($bodyParts as $index => $part)
        {!! $part !!}

        {{-- Sisipkan gambar setelah setiap 2 paragraf, kalau masih ada --}}
        @if(($index + 1) % 2 == 0 && isset($images[$imgIndex]))
          @if(isset($images[$imgIndex + 1]))
            {{-- Kalau ada dua gambar berikutnya --}}
            <div class="inline-image-pair">
              <img src="{{ asset('uploads/blogs/'.$images[$imgIndex]->filename) }}" alt="Gambar pendukung">
              <img src="{{ asset('uploads/blogs/'.$images[$imgIndex + 1]->filename) }}" alt="Gambar pendukung">
            </div>
            @php $imgIndex += 2; @endphp
          @else
            {{-- Kalau tinggal satu gambar --}}
            <div class="inline-image">
              <img src="{{ asset('uploads/blogs/'.$images[$imgIndex]->filename) }}" alt="Gambar pendukung">
            </div>
            @php $imgIndex++; @endphp
          @endif
        @endif
      @endforeach
    </div>

    {{-- Penulis --}}
    @if(!empty($blog->author))
    <div class="blog-author">
      <img src="{{ asset('uploads/authors/'.$blog->author->photo ?? 'default.jpg') }}" alt="{{ $blog->author->name }}">
      <div class="author-info">
        <h5>Written by {{ ucwords($blog->author->name) }}</h5>
        <p>{{ $blog->author->bio ?? 'Penulis di platform Rasanya Lelang Karya yang berfokus pada seni dan budaya.' }}</p>
      </div>
    </div>
    @endif

    {{-- Artikel terkait --}}
    @if(!empty($relatedBlogs) && $relatedBlogs->count() > 0)
    <div class="related-posts mt-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Artikel atau postingan terkait</h4>
      </div>

      <div class="related-scroll">
        @foreach($relatedBlogs as $rel)
          <div class="related-card">
            <a href="{{ url('blog/'.$rel->slug) }}" class="text-decoration-none text-dark">
              <img src="{{ asset('uploads/blogs/'.$rel->image) }}" alt="{{ $rel->title }}">
              <h6>{{ $rel->title }}</h6>
              <p>{{ $rel->author->name ?? 'Anonim' }}</p>
            </a>
          </div>
        @endforeach

        {{-- Card "See More" di akhir --}}
        <div class="related-card see-more-card">
          <a href="{{ url('/blogs') }}" class="see-more-link">
            <div class="see-more-circle">
              <span>Lihat Lebih<br> Banyak</span>
            </div>
          </a>
        </div>
      </div>

    </div>
    @endif

  </div>
</section>
@endsection
