@extends('layouts._header')
@section('title','Edit Pembayaram')
@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/penjualan.php">Pembayaran</a></li>
            <li class="breadcrumb-item active" aria-current="page">ID : {{$pembelian->id}}</li>
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

    @php
        $today = date('Y-m-d H:i:s')
    @endphp
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('pelangganUtang.update', $pembelian->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <input type="text" class="form-control" name="id" value="{{ $pembelian->id }}" hidden>
                    <div class="form-group row">
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="tanggal_bukti">Tanggal Pembayaran</label>
                            <input type="datetime-local" class="form-control" name="tanggal_bukti" value="{{ $today }}">
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="tanggal_nota">Nomor Bukti</label>
                            <input type="text" class="form-control" name="nomor_bukti" value="{{ $pembelian->nomor_bukti }}" readonly>
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="nama_pelanggan" class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" name="nama_pelanggan" value="{{ $pembelian->nama_sales }}" readonly>

                            {{-- <select id="nama_pelanggan" name="nama_pelanggan" class="form-control">
                                <option value="{{ $pembelian->nama_pelanggan }}">{{ $pembelian->nama_pelanggan }}</option>
                                <option value=""></option>
                                {{-- @foreach ($pelanggan as $item)
                                <option value="{{ $item->nama_pelanggan }}">{{ $item->nama_pelanggan }}</option>
                                @endforeach 
                            </select> --}}
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="nama_akun">Nama Akun</label>
                            <select name="nama_akun" id="nama_akun" class="form-control">
                                $@foreach ($kas as $item)
                                    <option value="{{$item->nama_akun}}">{{$item->nama_akun}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5 mb-5 mr-5">
                            <label for="jumlah">Jumlah</label>
                            <input type="text" class="form-control" name="jumlah" value="Rp. {{ number_format($pembelian->jumlah, 0, ',', '.') }}" required>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label for="kategori" class="form-label">Kategori Supplier</label>
                            <select id="kategori" name="kategori" class="form-control" style="width: 430px;">
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->name }}" {{ $item->name == $pembelian->kategori ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="nama_sales" value="{{$pembelian->nama_sales}}">
                        <input type="hidden" name="nama_user" value="{{Auth::user()->username}}">
                        <input type="hidden" name="tanggal_log" value="{{Auth::user()->tanggal_log}}">
                    </div>
                    <button type="submit" class="btn btn-md btn-primary mr-4"> Simpan</button>
                    <a class="btn btn-md btn-secondary" href="/detail-pembelian-pelanggan/{{$pembelian->nama_sales}}">Cancel</a>
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