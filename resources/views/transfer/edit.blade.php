@extends('layouts._header')

@section('title', 'Edit Transfer ' . $transfer->nama_akun)

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/akun_kas/{{$transfer->nama_akun}}">Kembali ke Kas</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Transfer: {{$transfer->nama_akun}}</li>
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
                <form action="{{ route('transfers.update', $transfer->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Specify the HTTP method for updating -->
            
                    <div class="form-group">
                        <label for="from_account">From Account</label>
                        <input type="text" class="form-control" id="from_account" name="from_account" value="{{ $transfer->nama_akun }}" readonly required>
                    </div>
                    
                    <div class="form-group">
                        <label for="to_account">To Account</label>
                        <select name="to_account" id="to_account" class="form-control">
                            @foreach ($kas->filter(function ($item) use ($transfer) {
                                return $item->nama_akun !== $transfer->nama_akun;
                            }) as $item)
                                <option value="{{ $item->nama_akun }}" {{ $item->nama_akun == $transfer->toAccount->nama_akun ? 'selected' : '' }}>
                                    {{ $item->nama_akun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
            
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ $transfer->jumlah * -1 }}" min="1" required>
                    </div>
            
                    <div class="form-group">
                        <label for="keterangan">Description</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4">{{ old('keterangan', $transfer->keterangan) }}</textarea>
                    </div>
            
                    <button type="submit" class="btn btn-primary">Update Transfer</button>
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
                id: "{{ $transfer->nomor_nota }}",
                text: "{{ $transfer->nomor_nota }}"
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
                id: "{{ $transfer->nama_pelanggan }}",
                text: "{{ $transfer->nama_pelanggan }}"
            };

            var newOption = new Option(defaultNamaPelanggan.text, defaultNamaPelanggan.id, true, true);
            $('#nama_pelanggan').append(newOption).trigger('change');
    </script>
@endsection
