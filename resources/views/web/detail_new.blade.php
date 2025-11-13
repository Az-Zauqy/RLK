@extends('web.partials.layout_new')

<!-- Section untuk menandai menu aktif di layout utama -->
@section('lelang', 'aktiv')

<!-- Push CSS khusus halaman detail (bisa diisi jika perlu custom style) -->
@push('css')
    <style type="text/css">

    </style>
@endpush

<!-- Mulai konten utama halaman -->
@section('content')
    <section class="p-5" style="background: #f3f6f8;">
        <div class="" id="app">

            {{-- Grid utama: gambar, info produk, dan panel bid/chat --}}
            <div class="row gx-3 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-3">

                {{-- Kolom kiri: gambar utama dan gallery --}}
                <div class="col col-mobile">
                    <div class="single-figure">
                        {{-- Gambar utama produk --}}
                        <img src="{{ asset($product->imageUtama->path ?? 'assets/img/default.jpg') }}" id="display">
                    </div>
                    <div class="d-flex mt-3">
                        {{-- Gallery gambar tambahan --}}
                        @foreach ($product->images as $img)
                            <div class="flex-figure" src="{{ asset($img->path ?? 'assets/img/default.jpg') }}">
                                <img src="{{ asset($img->path ?? '') }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kolom tengah: detail produk --}}
                <div class="col col-mobile">
                    {{-- Judul produk --}}
                    <h1 class="single-title">{{ $product->title }}</h1>
                    {{-- Harga produk --}}
                    <div class="text-danger single-price">{{ $product->price_str }}</div>
                    {{-- Kelipatan bid --}}
                    <div class="d-flex caption-produk">
                        <div class="flex-grow1">Kelipatan Bid</div>
                        <div class="flex-grow2">{{ $product->kelipatan_bid }}</div>
                    </div>
                    {{-- Informasi produk --}}
                    <div class="pt-2 fw-bold">Informasi Produk</div>
                    <div class="d-flex caption-produk">
                        <div class="flex-grow1">Berat</div>
                        <div class="flex-grow2">{{ $product->weight }}</div>
                    </div>
                    <div class="d-flex caption-produk">
                        <div class="flex-grow1">Kondisi</div>
                        <div class="flex-grow2">{{ $product->kondisi }}</div>
                    </div>
                    <div class="d-flex caption-produk">
                        <div class="flex-grow1">Kategori</div>
                        <div class="flex-grow2">
                            {{-- Link ke kategori --}}
                            <a href="{{ route('products.category', $product->kategori->slug) }}"
                                class="text-decoration-none text-dark">
                                {{ $product->kategori->name }}
                            </a>
                        </div>
                    </div>
                    <div class="d-flex caption-produk">
                        <div class="flex-grow1">Seniman</div>
                        <div class="flex-grow2">
                            {{-- Link ke seniman --}}
                            <a href="{{ route('products.seniman', $product->karya->slug) }}"
                                class="text-decoration-none text-dark">
                                {{ $product->karya->name }}
                            </a>
                        </div>
                    </div>
                    <div class="d-flex caption-produk">
                        <div class="flex-grow1">Tanggal berakhir</div>
                        <div class="flex-grow2">{{ $product->end_date_indo }}</div>
                    </div>
                    {{-- Info pengiriman --}}
                    <div class="row mt-4">
                        <div class="col-md-12 single-send fw-bold">
                            Pengiriman
                        </div>
                        <div class="col-md-12 single-city">
                            Dikirim dari <span>Semarang</span>
                        </div>
                    </div>
                    {{-- Info asuransi jika ada --}}
                    @if ($product->asuransi)
                        <div class="row mt-2">
                            <div class="col-md-12 single-send fw-bold">
                                Proteksi Kerusakan
                            </div>
                            <div class="col-md-12 single-city">
                                melindungi produkmu dari risiko rusak maupun kerugian selama 6 bulan
                            </div>
                        </div>
                    @endif
                    {{-- Deskripsi produk --}}
                    <div class="pt-2 fw-bold m-block">Deskripsi</div>
                    <div class="single-desc m-block">
                        {!! $product->description !!}
                    </div>
                    {{-- Tombol bid --}}
                    <div class="d-block w-100 bid-button py-2">
                        <button type="button" class="btn btn-danger btn-block w-100" id="bid-button">Bid</button>
                    </div>
                </div>

                {{-- Kolom kanan: panel bid/chat --}}
                <div class="col" id="bid-mobile">
                    <div class="panel panel-default rounded-4">
                        {{-- Header panel bid/chat --}}
                        <div class="panel-heading panel-heading-mobile">
                            <div class="bid-foto">
                                <div class="d-flex p-2">
                                    <div class="d-block px-2 text-white" id="bid-off">
                                        <i class="fa fa-times"></i>
                                    </div>
                                    <div class="d-block text-white">
                                        Ruang Lelang
                                    </div>
                                </div>
                                <div class="d-flex panel-img">
                                    <div class="panel-figure">
                                        <img src="{{ asset($product->imageUtama->path ?? 'assets/img/default.jpg') }}">
                                    </div>
                                    <div class="d-block">
                                        <p class="fw-bold mb-0">{{ $product->title }}</p>
                                        <p>{{ $product->price_str }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Panel chat/bid, jika user sudah login --}}
                        @if (Auth::check() == true)
                            <div class="panel-body panel-body-mobile">
                                {{-- Komponen chat Vue --}}
                                <chat-messages :messages="messages":user="{{ Auth::user() }}"></chat-messages>
                            </div>
                            <div class="panel-footer panel-footer-mobile">
                                {{-- Form chat/bid Vue --}}
                                <chat-form v-on:messagesent="addMessage"
                                    :user="{{ Auth::user() }}":produk="{{ $product->id }}"
                                    :kelipatan="{{ $product->kelipatan }}" :price="{{ $product->price }}"></chat-form>
                            </div>
                        @else
                            {{-- Jika belum login, tampilkan chat statis dan tombol login --}}
                            <div class="panel-body panel-body-mobile">
                                <ul class="chat">
                                    @foreach ($bids as $message)
                                        <li class="left clearfix">
                                            <div class="chat-body clearfix">
                                                <div class="header">
                                                    <strong class="primary-font">
                                                        {{ $message['user']->name }}
                                                    </strong>
                                                </div>
                                                <p>
                                                    {{ $message['message'] }}
                                                </p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="panel-footer panel-footer-mobile">
                                <a class="btn btn-outline-secondary btn-sm rounded-0 border-1 w-100"
                                    href="{{ url('/login') }}">
                                    Login
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Kolom deskripsi dan kelengkapan karya untuk tampilan mobile --}}
                <div class="col-md-12 desk-mobile pb-3">
                    <div class="pt-2 fw-bold">Deskripsi</div>
                    <div class="single-desc">
                        {!! $product->description !!}
                        {{-- Menampilkan kelengkapan karya jika ada --}}
                        @if (isset($product) && !empty($product->kelengkapans) && count($product->kelengkapans) > 0)
                            <p class="pt-2 fw-bold">Kelengkapan Karya :</p>
                            <ul>
                                @foreach ($product->kelengkapans as $pk)
                                    <li>{{ ucwords($pk->name) }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<!-- Section untuk script JS halaman detail -->
@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script type="text/javascript">
        // Ganti gambar utama saat thumbnail diklik
        $('.flex-figure').click(function() {
            var image = document.getElementById("display");
            image.src = this.getAttribute('src');
        });
        // Tampilkan panel bid/chat saat tombol bid diklik
        $('#bid-button').click(function() {
            var bm = document.getElementById("bid-mobile");
            bm.classList.add("bid-active");
        });
        // Sembunyikan panel bid/chat saat tombol close diklik
        $('#bid-off').click(function() {
            var bm = document.getElementById("bid-mobile");
            bm.classList.remove("bid-active");
        });
        // Listener untuk event chat (Laravel Echo)
        Echo.private('chat')
            .listen('MessageSent', (e) => {
                console.log("disini", e);
                // document.getElementById('latest_trade_user').innerText = e.trade;
            })
    </script>
@endsection
