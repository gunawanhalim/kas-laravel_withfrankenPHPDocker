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
    background-color: #b94444;
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
    color: #ee6060;
}

.card-body .last-update {
    margin-top: 4px;
    font-size: 0.8em;
    color: #535151;
}

.card-footer {
    padding: 15px;
    display: flex;
    justify-content: space-between;
    background-color: #f9f9f9;
    border-top: 1px solid #eee;
}

.btn-primary {
    background-color: #c2888b;
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
    background-color: #a0455d;
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
            $saldo = $data['total_pemasukan'];
            $formattedSaldo = number_format(abs($saldo), 0, ',', '.');
            $colorClass = $saldo < 0 ? 'text-success' : 'text-danger';
        @endphp
    
        <div class="card-body">
            {{-- <p>{{ $data['id_akun_kas'] }}</p> --}}
            <p class="balance " style="font-size: 18px;">
                Saldo: <span class="{{ $colorClass }}">Rp {{ $saldo < 0 ? '-' : '' }}{{ $formattedSaldo }}</span>
            </p>
            <p class="last-update">Terakhir diperbarui: <br>{{ $data['tanggal_bukti']; }}</p>
        </div>    
        <div class="card-footer">
            <a class="btn btn-primary" href="/showCredit.php/{{$nama_akun}}">Detail</a>
            <a class="btn btn-info" href="/exportUtang">Export</a>
        </div>
    </div>
    @endforeach

</div>
<!-- Modal -->
<div class="modal fade" id="newTransactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Transaksi Baru untuk <span id="namaAkunModal"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Form untuk transaksi baru -->
          <form id="newTransactionForm" action="{{ route('debit.store') }}" method="POST">
            @csrf
              <input type="hidden" id="nama_akun_input" name="nama_akun" hidden>
              <input type="hidden" id="kategori_input" name="kategori" value="1">
              <input type="hidden" id="nama_user_input" name="nama_user" value="{{Auth::user()->name}}">
              <input type="hidden" id="tanggal_login_input" name="tanggal_log" value="{{ Auth::user()->tanggal_login}}">
              <div class="row">
                <div class="mb-3" style="margin-right: 20px;">
                    <label for="nomor_bukti_input">Nomor Bukti</label>
                    <input type="text" class="form-control" id="nomor_bukti_input" name="nomor_bukti" autocomplete="off">
                </div>
                <div class="mb-3" style="margin-right: 20px;">
                    <label for="nomor_nota_input">Nomor Nota</label>
                    <input type="text" class="form-control" id="nomor_nota_input" name="nomor_nota" autocomplete="off">
                </div>
                <div class="mb-3" style="margin-right: 20px;">
                    <label for="jumlah_input">Jumlah</label>
                    <input type="text" class="form-control" id="jumlah_input" oninput="formatAngka(this)" name="jumlah" autocomplete="off">
                </div>
                <div class="mb-3" style="margin-right: 20px;">
                    <label for="nama_pelanggan_input">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="nama_pelanggan_input" name="nama_pelanggan" autocomplete="off">
                </div>
                <div class="mb-3" style="margin-right: 20px;">
                    <label for="kategori_sub">Kategori</label>
                    <select name="kategori_sub" id="kategori_sub_input" class="form-control">
                        <option value="Ongkos Makan">Ongkos Makan</option>
                    </select>
                </div>
                <div class="mb-3" style="margin-right: 20px;">
                    <label for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" cols="30" rows="3" style="width: 420px;"></textarea >
                </div>
                <div class="mb-3" style="margin-right: 20px; text-align:center; ">
                    <label for="tanggal_bukti_input">Tanggal Bukti</label>
                    <input type="datetime-local" class="form-control" id="tanggal_bukti_input" name="tanggal_bukti" autocomplete="off" style="width: 420px;">
                </div>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Submit</button>
              </div>
          </form>
        </div>

      </div>
    </div>
  </div>
<!-- End Modal -->
<div class="my-5">
    {{ $kas_bank->links() }}
</div>
<script src="../assets/js/jquery.min.js"></script>
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

</script>
@endsection