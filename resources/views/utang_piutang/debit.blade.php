@extends('layouts._header')
@section('title','Utang')

@section('content')

<style>


.card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 250px;
    margin-left: 43px;
    margin-right: 43px;
    margin-top: 30px;
    margin-bottom: 20px;
    overflow: hidden;
}

.card-header {
    background-color: #1e8321;
    color: white;
    padding: 15px;
    text-align: center;
}

.card-header h2 {
    margin: 0;
}

.card-body {
    padding: 20px;
}

.card-body .balance {
    font-size: 1.5em;
    margin: 0;
    color: #333;
}

.card-body .balance span {
    font-weight: bold;
    color: #60ee64;
}

.card-body .last-update {
    margin-top: 4px;
    font-size: 0.8em;
    color: #797575;
}

.card-footer {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    background-color: #f9f9f9;
    border-top: 1px solid #eee;
}

.btn-primary {
    background-color: #88c28a;
    color: white;
    border: none;
    padding: 10px 15px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #45a049;
}

.btn-secondary {
    background-color: #ddd;
    color: #333;
    border: none;
    padding: 10px 15px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-secondary:hover {
    background-color: #ccc;
}

</style>
    <!-- partial -->
    <div class="my-2">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container mt-4">
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
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Utang</li>
        </ol>
    </nav>
</div>
<div class="row">
    @foreach ($balances as $nama_akun => $data)
    <div class="card">
    
        <div class="card-header">
            <h2>{{ $nama_akun }}</h2>
        </div>
    
        @php
            $saldo = $data['total_pengeluaran'];
            $formattedSaldo = number_format(abs($saldo), 0, ',', '.');
            $colorClass = $saldo < 0 ? 'text-danger' : 'text-success';
        @endphp
    
        <div class="card-body">
            {{-- <p>{{ $data['id_akun_kas'] }}</p> --}}
            <p class="balance " style="font-size: 18px;">
                Saldo: <span class="{{ $colorClass }}">Rp {{ $saldo < 0 ? '-' : '' }}{{ $formattedSaldo }}</span>
            </p>
            <p class="last-update">Terakhir diperbarui: <br>{{ $data['tanggal_bukti']; }}</p>
        </div>
    
        <div class="card-footer">
            <a class="btn btn-primary" href="/showDebit.php/{{$nama_akun}}">Detail</a>
            <a class="btn btn-info" href="/exportPiutang">Export</a>
        </div>
    </div>
    @endforeach

</div>
<!-- Modal -->

<!-- End Modal -->
<div class="my-5">
    {{ $kas_bank->links() }}
</div>
<script src="../assets/js/jquery.min.js"></script>
<script src="{{ asset('node_modules/select2/dist/js/select2.min.js') }}"></script>

<script>
        $(document).ready(function() {
            // Ambil nomor bukti saat halaman dimuat
            fetchNomorBukti();

            // Fungsi untuk mengambil nomor bukti baru dari server
            function fetchNomorBukti() {
                $.ajax({
                    url: '/generate-nomor-bukti',
                    method: 'GET',
                    success: function(data) {
                        $('#nomor_bukti_input').val(data.nomor_bukti);
                    },
                    error: function() {
                        alert('Gagal mengambil nomor bukti.');
                    }
                });
            }
        });

document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk menangani klik tombol transaksi baru
    var newTransactionButtons = document.querySelectorAll('[id^="newTransactionButton"]');
    newTransactionButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Mengambil nilai nama_akun dari atribut data-item-id
            var namaAkun = this.getAttribute('data-item-id');
            // Menetapkan nilai nama_akun ke dalam input di dalam modal
            document.getElementById('nama_akun_input').value = namaAkun;
            // Menetapkan nama_akun ke dalam teks di dalam modal header
            document.getElementById('namaAkunModal').textContent = namaAkun;
        });
    });
});

    // Memformat angka dengan menambahkan titik sebagai pemisah ribuan dan "IDR" di awal input
    function formatAngka(input) {
        let nilai = input.value;

        nilai = nilai.replace(/\D/g, '');

        nilai = nilai.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        input.value = 'IDR ' + nilai;
    }

    $(document).ready(function() {
    // Fungsi untuk menginisialisasi Select2
    function initSelect2() {
        $('#nama_pelanggan_input').select2({
            ajax: {
                url: '/nama_pelangganSearch',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama_pelanggan
                            };
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Cari pelanggan',
            minimumInputLength: 1
        });
    }

    // Panggil fungsi inisialisasi saat modal ditampilkan
    $('#newTransactionModal').on('shown.bs.modal', function () {
        initSelect2();
    });
});
</script>
@endsection