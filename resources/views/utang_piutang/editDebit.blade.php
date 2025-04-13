@extends('layouts._header')

@section('title', 'Edit Utang ' . $debit->nomor_bukti)

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Nomor Bukti: {{$debit->nomor_bukti}}</li>
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

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Utang</h4>
                <form action="{{ route('debit.update', $debit->nomor_bukti) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama_akun">Nama Akun</label>
                        <input type="text" class="form-control" id="nama_akun" name="nama_akun" value="{{ $debit->nama_akun }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_user">Nama User</label>
                        <input type="text" class="form-control" id="nama_user" name="nama_user" value="{{ $debit->nama_user }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nomor_nota" class="form-label">Nomor Nota</label>
                        <select id="nomor_nota" name="nomor_nota" class="form-control">
                         <option value=""></option>
                     </select>
                    </div>
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control">
                            @if($debit->kategori == "1")
                            <option value="1">Pemasukan</option>
                            <option value="2">Pengeluaran</option>
                            @elseif ($debit->kategori =="2")
                            <option value="2">Pengeluaran</option>
                            <option value="1">Pemasukan</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Nama Kategori</label>
                        <select name="subcategories_id" id="subcategories_id" class="form-control">
                            @foreach ($kategori as $item)
                                <option value="{{$item->name}}">
                                    {{$item->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ $keterangan }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Jumlah</label>
                        <input type="text" class="form-control" id="jumlah" name="jumlah" value="IDR {{ number_format($debit->jumlah, 0, ',', '.') }}" oninput="formatAngka(this)" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                        <select id="nama_pelanggan" name="nama_pelanggan" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_bukti">Tanggal Bukti</label>
                        <input type="dateTime-local" class="form-control" id="tanggal_bukti" name="tanggal_bukti" value="{{ $debit->tanggal_bukti }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('node_modules/select2/dist/js/select2.min.js') }}"></script>
    <script>
            function formatAngka(input) {
        let nilai = input.value;

        nilai = nilai.replace(/\D/g, '');

        nilai = nilai.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        input.value = 'IDR ' + nilai;
    }
            $('#nomor_nota').select2({
                ajax: {
                    url: '/nomorNotaSearch',
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
                                    id: obj.nomor_nota,
                                    text: obj.nomor_nota,
                                };
                            })
                        };
                    },
                    cache: true
                },
                placeholder: 'Cari Nomor Nota',
                minimumInputLength: 1,
                templateResult: function (data) {
                    return data.text;
                },
                templateSelection: function (data) {
                    return data.text;
                }
            });

            // Memuat nilai default
            var defaultNota = {
                id: "{{ $debit->nomor_nota }}",
                text: "{{ $debit->nomor_nota }}"
            };

            var newOption = new Option(defaultNota.text, defaultNota.id, true, true);
            $('#nomor_nota').append(newOption).trigger('change');

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
                            text: obj.nama_pelanggan
                            // alamat: obj.alamat
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
            // $('#alamat').val(data.alamat); // Isi otomatis input alamat
            return data.text;
        }
    });

    var defaultNamaPelanggan = {
                id: "{{ $debit->nama_pelanggan }}",
                text: "{{ $debit->nama_pelanggan }}"
            };

            var newOption = new Option(defaultNamaPelanggan.text, defaultNamaPelanggan.id, true, true);
            $('#nama_pelanggan').append(newOption).trigger('change');
    </script>
@endsection
