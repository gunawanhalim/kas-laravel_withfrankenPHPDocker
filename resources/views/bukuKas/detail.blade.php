@extends('layouts._header')
@section('title','Detail Kas ' . $showdetail->nama_akun)
@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Kembali</a></li>
            <li class="breadcrumb-item active" aria-current="page">ID : {{$showdetail->id}}</li>
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
                        <label for="tanggal_bukti">Tanggal Bukti</label>
                        <input type="text" class="form-control" readonly value="{{ \Carbon\Carbon::parse($showdetail->tanggal_bukti)->format('d/m/Y') }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nama_akun">Nama Akun</label>
                        <input type="text" class="form-control" readonly value="{{ $showdetail->nama_akun }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="kategori">Kategori</label>
                        <input type="text" class="form-control" readonly value="{{ $showdetail->kategori }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" class="form-control" readonly value="Rp. {{ number_format($showdetail->jumlah, 0, ',', '.') }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" readonly value="{{ $showdetail->keterangan }}" >
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nama_user">Nama User</label>
                        <input type="text" class="form-control" readonly value="{{ $showdetail->nama_user }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="tanggal_log">Tanggal Log</label>
                        <input type="text" class="form-control" readonly value="{{ $showdetail->tanggal_log }}" alt="Tanggal saat di input pada hari itu juga">
                    </div>
                </div>
                <a class="btn btn-success" href="{{ route('kas_bank.edit', $showdetail->id) }}">Edit</a>                        
                <a href="/beranda.php" class="btn btn-light">Cancel</a>
            </div>
        </div>
    </div>
@endsection