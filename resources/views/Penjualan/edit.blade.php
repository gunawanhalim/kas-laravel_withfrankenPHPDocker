@extends('layouts._header')
@section('title','Edit '.$sale->nomor_nota)
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
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('penjualan.update', $sale->nomor_nota) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="text" class="form-control" name="nomor_nota" value="{{ $sale->nomor_nota }}" hidden>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="tanggal_nota">Tanggal Nota</label>
                            <input type="datetime-local" class="form-control" name="tanggal_nota" value="{{ $sale->tanggal_nota }}">
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="jatuh_tempo">Tanggal Jatuh Tempo</label>
                            <input type="datetime-local" class="form-control" name="jatuh_tempo" value="{{ $sale->jatuh_tempo }}">
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                            <select id="nama_pelanggan" name="nama_pelanggan" class="form-control">
                                <option value="{{ $sale->nama_pelanggan }}">{{ $sale->nama_pelanggan }}</option>
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="alamat">Alamat</label>
                            <input id="alamat" name="alamat" type="text" class="form-control" placeholder="tidak usah di isi jika sama" value="{{ $sale->alamat }}" readonly>
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="total">Jumlah</label>
                            <input type="text" class="form-control" name="total" value="Rp. {{ number_format($sale->total, 0, ',', '.') }}">
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="nama_sales">Nama Sales</label>
                            <input type="text" class="form-control" value="{{ $sale->nama_sales }}" name="nama_sales" >
                        </div>
                    </div>
                    <button type="submit" class="btn btn-md btn-primary mr-4"> Simpan</button>
                    <a class="btn btn-md btn-secondary" href="/penjualan">Cancel</a>
                </form>


            </div>
        </div>
    </div>
    <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('node_modules/select2/dist/js/select2.min.js') }}"></script>
    <script>
    $('#nama_pelanggan').select2({
        ajax: {
            url: '/nama_pelangganSearch',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // istilah pencarian
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function(obj) {
                        return {
                            id: obj.nama_pelanggan,
                            text: obj.nama_pelanggan,
                            alamat: obj.alamat
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: 'Cari pelanggan',
        minimumInputLength: 1,
        templateResult: function (data) {
            return data.text;
        },
        templateSelection: function (data) {
            $('#alamat').val(data.alamat); // Isi otomatis input alamat
            return data.text;
        }
    });

</script>
@endsection