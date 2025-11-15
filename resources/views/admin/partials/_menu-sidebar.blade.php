<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            {{-- <i class="fas fa-laugh-wink"></i> --}}
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Lelang</sup></div>
    </a>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider">
    <div class="sidebar-heading">
        Master
    </div>
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-cart-plus"></i>
            <span>Products</span>
        </a>
        <div id="collapseTwo" class="collapse @yield('collapseMaster')" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item @yield('addproduct')" href="{{ route('master.product.create') }}">Add Product</a>
                <a class="collapse-item @yield('product')" href="{{ route('master.product.index') }}">All Products</a>
                <a class="collapse-item @yield('kategoriproduct')"
                    href="{{ route('master.kategori.index', ['cat_type' => 'product']) }}">Categories</a>
                <a class="collapse-item @yield('karya')" href="{{ route('master.karya.index') }}">Seniman</a>
                <a class="collapse-item @yield('kelengkapan')" href="{{ route('master.kelengkapan.index') }}">Kelengkapan
                    Karya</a>

            </div>
        </div>
    </li>
    {{-- MERCH PRODUCT --}}
    <li class="nav-item @yield('collapseMerchProducts')">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMerchProducts"
            aria-expanded="true" aria-controls="collapseMerchProducts">
            <i class="fas fa-cart-plus"></i>
            <span>Merch Products</span>
        </a>
        <div id="collapseMerchProducts" class="collapse @yield('collapseMerchProducts')" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item @yield('size')" href="{{ route('master.size.index') }}">All Size</a>
                <a class="collapse-item @yield('addSize')" href="{{ route('master.size.create') }}">Add Size</a>
            </div>
        </div>
    </li>

    <li class="nav-item @yield('events')"> <a class="nav-link collapsed" href="#" data-toggle="collapse"
            data-target="#collapseEvents" aria-expanded="true" aria-controls="collapseEvents">
            <i class="fas fa-calendar-alt"></i> <span>Events</span>
        </a>
        <div id="collapseEvents" class="collapse @yield('collapseEvents')" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item @yield('addEvent')" href="{{ route('admin.events.create') }}">Add Event</a>
                <a class="collapse-item @yield('allEvents')" href="{{ route('admin.events.index') }}">All Events</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBlog"
            aria-expanded="true" aria-controls="collapseBlog">
            <i class="fas fa-book"></i>
            <span>Blog</span>
        </a>
        <div id="collapseBlog" class="collapse @yield('collapseBlog')" aria-labelledby="headingTwo"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item @yield('addblog')" href="{{ route('admin.blogs.create') }}">Add Blogs</a>
                <a class="collapse-item @yield('blogs')" href="{{ route('admin.blogs.index') }}">All Blogs</a>
                <a class="collapse-item @yield('kategoriblog')"
                    href="{{ route('master.kategori.index', ['cat_type' => 'blog']) }}">Categories</a>
                {{-- <a class="collapse-item @yield('tags')" href="{{route('master.tags.index')}}">Tags</a> --}}

            </div>
        </div>
    </li>
    <li class="nav-item @yield('post')">
        <a class="nav-link collapsed" href="{{ route('admin.posts.index') }}">
            <i class="fas fa-list"></i>
            <span>Pages</span>
        </a>
    </li>
    <li class="nav-item @yield('slider')">
        <a class="nav-link collapsed" href="{{ route('master.sliders.index') }}">
            <i class="fas fa-image"></i>
            <span>Sliders</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item @yield('post')">
        <a class="nav-link collapsed" href="{{ route('admin.daftar-penawaran.index') }}">
            <i class="fas fa-cart-plus"></i>
            <span>Daftar Penawaran</span>
        </a>
    </li>
    <li class="nav-item @yield('slider')">
        <a class="nav-link collapsed" href="{{ route('admin.daftar-pemenang.index') }}">
            <i class="fas fa-users"></i>
            <span>Pemenang Lelang</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    {{-- ... (Sisa menu Anda) ... --}}

    @if (Auth::user()->access == 'admin')
        <li class="nav-item @yield('setting')">
            <a class="nav-link collapsed" href="{{ route('setting.data') }}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Setting</span>
            </a>
        </li>
        <li class="nav-item @yield('social')">
            <a class="nav-link collapsed" href="{{ route('setting.social') }}">
                <i class="fas fa-fw fa-share-alt"></i>
                <span>Social</span>
            </a>
        </li>

        <li class="nav-item @yield('user')">
            <a class="nav-link collapsed" href="{{ route('admin.user.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span>
            </a>
        </li>
    @endif
</ul>
