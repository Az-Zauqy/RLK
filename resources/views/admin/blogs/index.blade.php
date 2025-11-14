@extends('admin.partials._layout')
@section('title','Daftar Blogs')
@section('collapseBlog','show')
@section('blogs','active')
@section('css')
<!-- Custom styles for this post -->
<link href="{{asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- Begin post Content -->
<div class="container-fluid">
    <!-- post Heading -->
    <h1 class="h5 mb-4 text-gray-800">Daftar 
        <small>Blogs</small>
        <a href="{{route('admin.blogs.create')}}" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus-circle"></i> Tambah Blog</a>
    </h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            {{-- Success notification --}}
            @include('admin.partials._success')
            <div class="table-responsive">
                <table class="table table-bordered table-sm" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Title</th>
                            <th>Published</th>
                            <th width="15%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $no=1;
                        @endphp
                        @foreach($blogs as $blog)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>
                                {{ucfirst($blog->title)}}<br>
                                @if($blog->kategori_name)
                                <span class="badge bg-primary text-white rounded-0">
                                    {{ ucfirst($blog->kategori_name) }}
                                </span>
                                @endif

                                <span class="badge bg-danger text-white rounded-0">
                                    {{$blog->status}}
                                </span>
                            </td>
                            <td>{{$blog->created_at}}</td>
                            <td class="text-center">
                                @if($blog->status == 'PUBLISHED')
                                 <a href="{{route('admin.blogs.status',$blog->id)}}" class="btn btn-sm btn-primary rounded-0">
                                    <i class="fa fa-xs fa-check"></i>
                                </a>
                                @else
                                <a href="{{route('admin.blogs.status',$blog->id)}}" class="btn btn-sm btn-warning rounded-0">
                                    <i class="fa fa-xs fa-times"></i>
                                </a>
                                @endif
                                <a href="{{route('admin.blogs.edit',$blog->id)}}" class="btn btn-sm btn-info rounded-0">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>
                                <form action="{{route('admin.blogs.destroy',[$blog->id])}}" method="post" class="d-inline">
                                    @method('delete')
                                    @csrf
                                    <button onclick="return confirm('Are you sure want to delete this record?')"
                                        type="submit" class="btn btn-danger btn-sm rounded-0">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->
@endsection
@section('js')
    <!-- post level plugins -->
    <script src="{{asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script type="text/javascript">
        // Call the dataTables jQuery plugin
        $(document).ready(function() {
          $('#dataTable').DataTable();
        });
    </script>
@endsection