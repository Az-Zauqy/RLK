@extends('web.partials.layout')
@section('home','aktiv')

{{-- 
================================
1. CSS (Menggunakan Owl Carousel)
================================
--}}
@section('css')
    <link href="{{ asset('css/home_new.css') }}" rel="stylesheet">
    
    <link href="{{asset('theme/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet" />
    <link href="{{asset('theme/owlcarousel/assets/owl.theme.default.min.css')}}" rel="stylesheet" />
@endsection


{{-- 
================================
2. KONTEN
================================
--}}
@section('content')

<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1>Koleksi Karya Seniman Terbaik dari Indonesia</h1>
                <p>Temukan Nilai Sesungguhnya dari Sebuah Karya Melalui Lelang Terbuka di Rasanya Lelang Karya</p>
                <form action="{{route('web.search')}}" method="GET" class="hero-search">
                    <input type="text" class="form-control search-input-none" placeholder="Cari" aria-label="Recipient's username" aria-describedby="basic-addon2" name="q">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container"> 
        <div class="row">
            <div class="col-md-12"> 
                <div class="carousel slide slider-container" id="myCarousel" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($sliders as $idx => $slide)
                        <div class="carousel-item @if($idx == 0) active @endif">
                            <img class="d-block w-100" src="{{asset('uploads/sliders/'.$slide->image)}}">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                    
                    @if($featuredEvent) 
                    <div class="hero-overlay-box">
                        <h4>{{ $featuredEvent->title }}</h4>
                        <p>{{ $featuredEvent->subtitle }}</p>
                        @if($featuredEvent->status == 'coming_soon')
                            <span class="btn btn-detail" style="opacity: 1; cursor: default;">Segera Hadir</span>
                        @else
                            <a href="{{ $featuredEvent->link ?? '#' }}" class="btn btn-detail">Detail</a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding auction-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="section-title">General Auction</h2>
            </div>
        </div>

        <div class="main-content">
            <div class="owl-carousel owl-theme">
                
                @if($products->isNotEmpty())
                    @foreach($products as $produk) 
                    <div class="item">
                        <div class="card-auction">
                            <img src="{{asset($produk->imageUtama->path ?? 'assets/img/default.jpg')}}" alt="{{$produk->title}}">
                            
                            <div class="card-body">
                                <h5>{{ $produk->title }}</h5> 
                                <p class="text-decoration-none text-dark" href="{{route('products.category',$produk->kategori->slug)}}">{{$produk->kategori->name}}</p>
                                <a href="{{route('detail',$produk->slug)}}" class="btn btn-outline-custom">Bid Sekarang</a>
                            </div>

                        </div>
                    </div> @endforeach
                @endif
                
            </div> <div class="owl-theme">
                <div class="owl-controls">
                    </div>
            </div>
        </div> 
    </div>
</section>

@if(isset($blogs) && $blogs->isNotEmpty())
<section class="section-padding blog-section">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-6">
                <h2 class="section-title mb-0">Online Blog</h2>
            </div>
            <div class="col-6 text-end">
                <a href="{{ route('blogs') }}" class="blog-see-all-link">Semua postingan >></a>
            </div>
        </div>
        <div class="row">
            @foreach($blogs->take(2) as $blog)
            <div class="col-lg-6 mb-4 d-flex"> 
                <div class="card-blog">
                    <img src="{{asset('uploads/blogs/'.$blog->image)}}" alt="{{$blog->title}}">
                    <div class="card-body">
                        <h5>{{ $blog->title }}</h5>
                        <p>{!! Str::limit(strip_tags($blog->body), 150) !!}</p>
                        <a href="{{route('web.blog.detail',$blog->slug)}}" class="btn btn-outline-custom">Lihat lebih banyak</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection 
{{-- 
================================
3. JAVASCRIPT (Menggunakan Dots)
================================
--}}
@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    
    <script src="{{asset('theme/owlcarousel/owl.carousel.min.js')}}"></script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        
        // Script Carousel (Bootstrap)
        $('#myCarousel').carousel({
            interval: 2000
        });

        // Script Inisialisasi Owl Carousel (Menggunakan Dots)
        $('.main-content .owl-carousel').owlCarousel({
            loop: true,
            margin: 10, // Jarak antar kartu
            
            nav: false, // Panah mati
            dots: true,  // Dots aktif
            
            responsive:{
                0:{
                    items: 1 // 2 item di mobile (bisa di-swipe)
                },
                600:{
                    items: 3 // 3 item di tablet
                },
                1000:{
                    items: 3 // 3 item di desktop
                }
            }
        });
        
    });
    </script>
@endsection