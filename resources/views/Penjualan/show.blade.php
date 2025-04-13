@extends('layouts._header')
@section('title','Detail Nota')
@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/penjualan.php">Penjualan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nomor Nota : {{$sale->nomor_nota}}</li>
        </ol>
    </nav>
</div>
    <div class="container mt-4">
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nomor_nota">Nomor Nota</label>
                        <input type="text" class="form-control" readonly value="{{ $sale->nomor_nota }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="tanggal_nota">Tanggal Nota</label>
                        <input type="dateTime-local" class="form-control" readonly value="{{ $sale->tanggal_nota }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" class="form-control" readonly value="{{ $sale->nama_pelanggan }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" readonly value="{{ $sale->alamat }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nama_user">Nama User</label>
                        <input type="text" class="form-control" readonly value="{{ $sale->nama_user }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="tanggal_log">Tanggal Kejadian</label>
                        <input type="text" class="form-control" readonly value="{{ $sale->tanggal_log }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="total">Jumlah</label>
                        <input type="text" class="form-control" readonly value="Rp. {{ number_format($sale->total, 0, ',', '.') }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nama_sales">Nama Sales</label>
                        <input type="text" class="form-control" readonly value="{{ $sale->nama_sales }}" >
                    </div>
                    
                </div>
                <a class="btn btn-danger" href="{{ url()->previous() }}">Kembali</a>

            </div>
        </div>
    </div>
@endsection