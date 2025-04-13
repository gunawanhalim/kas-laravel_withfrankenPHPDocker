@extends('layouts._header')
@section('title','Edit ' . $showdetail->nama_akun)
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
                <form action="{{ route('kas_bank.update', ['id' => $showdetail->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                <div class="form-group row">
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="tanggal_bukti">Tanggal Bukti</label>
                        <input type="datetime-local" class="form-control" name="tanggal_bukti" id="tanggal_bukti" value="{{ \Carbon\Carbon::parse($showdetail->tanggal_bukti)->format('Y-m-d H:i:s') }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nama_akun">Nama Akun</label>
                        <select name="nama_akun" id="nama_akun" class="form-control">
                            <option value="{{ $showdetail->nama_akun }}">{{ $showdetail->nama_akun }}</option>
                            @foreach ($kas as $item)
                                @if ($item->nama_akun !== $showdetail->nama_akun)
                                    <option value="{{ $item->nama_akun }}">{{ $item->nama_akun }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="kategori">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control" onchange="updateSubcategoriesAndKategori(this)">
                            <option value="">Silahkan Pilih</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->kategori_id }}" data-name="{{ $item->name }}" {{ $item->kategori === $showdetail->kategori ? 'selected' : '' }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="subcategories_id" id="subcategories_id" value="{{ $showdetail->kategori_id }}">
                        <input type="hidden" name="kategori" id="kategori_name" value="{{ $showdetail->kategori }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" class="form-control" name="jumlah"  value="Rp. {{ number_format($showdetail->jumlah, 0, ',', '.') }}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control">{{ $showdetail->keterangan }}</textarea>
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="nama_user" hidden>Nama User</label>
                        <input type="text" class="form-control" name="nama_user" hidden  value="{{Auth::user()->username}}">
                    </div>
                    <div class="col-sm-5 mb-5 mr-5">
                        <label for="tanggal_log" hidden>Tanggal Log</label>
                        <input type="datetime-local" class="form-control" name="tanggal_log" hidden value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" alt="Tanggal saat di input pada hari itu juga">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a class="btn btn-danger" href="{{ url()->previous() }}">Kembali</a>

                </form>

            </div>
        </div>
    </div>
    <script>
    function updateSubcategoriesAndKategori(select) {
    var selectedOption = select.options[select.selectedIndex];
    var subcategoriesId = selectedOption.value;
    var kategoriName = selectedOption.getAttribute('data-name');

    document.getElementById('subcategories_id').value = subcategoriesId;
    document.getElementById('kategori_name').value = kategoriName;
    }
    </script>
@endsection