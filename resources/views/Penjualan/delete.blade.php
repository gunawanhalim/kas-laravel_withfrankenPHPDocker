@extends('layouts._header')
@section('title','Delete Penjualan')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/penjualan">Penjualan</a></li>
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
    <div class="card my-2" style="background-color:#a00000; width:400px;">
        <div class="card-body">
            <h5 class="card-title" style="color:#e6ebfc;">Anda mungkin memiliki Piutang atau Utang di Nomor Nota {{$sale->nomor_nota}}. Apa yang ingin Anda lakukan?</h5>
            <form action="/destroyPenjualan/{{$sale->nomor_nota}}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" value="{{$sale->nomor_nota}}">
                <button type="submit" class="btn btn-md btn-danger">Hapus</button>
                <a href="{{route('penjualan.index')}}" class="btn btn-md btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@endsection