@extends('admin.partials._layout')
@section('title','Create ukuran')
@section('collapseMerchProducts','show')
@section('addSize','active')
@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h5 mb-4 text-gray-800">Master
    <small>Ukuran</small>
    {{-- <a href="" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus-circle"></i> Create</a> --}}
    </h1>
    <div class="row">
        <div class="col-sm-5">

            <div class="card shadow mb-4 rounded-0">
                <div class="card-body">
                    {{ Form::open(array('route' => 'master.size.store')) }}
                    @include('admin.master.size.form')
                    <a href="{{ route('master.size.index') }}" class="btn btn-primary btn-sm rounded-0">Kembali</a>
                    {{ Form::submit('Simpan', array('class' => 'btn btn-primary btn-sm rounded-0')) }}
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
