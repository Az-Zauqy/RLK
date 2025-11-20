@extends('admin.partials._layout')
@section('title', 'Create kategori')
@section('collapseMerchProducts', 'show')
@section('addMerchCategory', 'active')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h5 mb-4 text-gray-800">Master
            <small>Kategori</small>
        </h1>
        <div class="row">
            <div class="col-sm-5">

                <div class="card shadow mb-4 rounded-0">
                    <div class="card-body">
                        {{ Form::open(['route' => 'master.merchcategory.store']) }}
                        @include('admin.master.merchcategory.form')
                        <a href="{{ route('master.merchcategory.index') }}"
                            class="btn btn-primary btn-sm rounded-0">Kembali</a>
                        {{ Form::submit('Simpan', ['class' => 'btn btn-primary btn-sm rounded-0']) }}
                        {{ Form::close() }}

                        {{-- Erors notification --}}
                        @include('admin.partials._errors')

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
