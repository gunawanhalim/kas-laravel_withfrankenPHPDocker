@extends('layouts._header')
@section('title', 'Pinjaman')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/link-kategori/{{$pinjaman->kategori}}">Kembali ke kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bayar Utang</li>
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
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
          <h4 class="card-title">PEMBAYARAN UTANG</h4>
          <form method="POST" action="{{ route('pinjaman.storebayar') }}">
            @csrf
            {{-- <input type="hidden" class="form-control" name="nama_akun" id="nama_akun" value="{{$nama_akun}}" autocomplete="off">/ --}}
            <div class="form-group row">
                <div class="col-sm-6 mb-2">
                    <label for="nomor_bukti" class="form-label">Nomor Bukti</label>
                   <input type="text" class="form-control" name="nomor_bukti" id="nomor_bukti" value="{{$pinjaman->nomor_bukti}}" readonly autocomplete="off">
                 </div>
                 @php
                 $today = date('Y-m-d H:i:s');
                @endphp
                 <div class="col-sm-6 mb-2">
                    <label for="tanggal_bukti" class="form-label">Tanggal Bukti</label>
                   <input type="datetime-local" class="form-control" name="tanggal_bukti" id="tanggal_bukti" placeholder="" value="{{$today}}">
                 </div>
                <div class="col-sm-6 mb-2">
                   <label for="nomor_nota" class="form-label">Nomor Nota</label>
                   <input type="text" class="form-control" name="nomor_nota" value="{{$pinjaman->nomor_nota}}" id="nomor_nota" autocomplete="off" readonly>

                   {{-- <select id="nomor_nota" name="nomor_nota" class="form-control">
                    <option value=""></option> --}}
                </select>
                </div>
                {{-- <div class="col-sm-6 mb-2">
                   <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                   <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" value="{{$pinjaman->nama_pelanggan}}" autocomplete="off" readonly>

                    {{-- <select id="nama_pelanggan" name="nama_pelanggan" class="form-control">
                    </select> 
                </div> --}}
                {{-- <div class="col-sm-6 mb-2">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select name="cariKategori" id="cariKategori" class="form-control">
                    </select>
                </div> --}}
                {{-- <input type="text" class="form-control" name="subcategories_id" id="subcategories_id" autocomplete="off" hidden>
                <input type="text" class="form-control" name="kategori" id="kategori" autocomplete="off" hidden> --}}

                 <div class="col-sm-6 mb-2">
                   <label for="nama_akun" class="form-label">Nama Akun</label>
                   {{-- <input type="text" class="form-control" name="nama_akun" id="nama_akun" value="{{$pinjaman->nama_akun}}" autocomplete="off" readonly> --}}

                    <select id="nama_akun" name="nama_akun" class="form-control">
                        @foreach ($kas as $item)
                        <option value="{{$item->nama_akun}}">{{$item->nama_akun}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 mb-2">
                    <label for="kategori" class="form-label">Kategori Supplier</label>
                    <select id="kategori" name="kategori" class="form-control">
                        @foreach ($kategori as $item)
                            <option value="{{ $item->name }}" {{ $item->name == $pinjaman->kategori ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                 <div class="col-sm-6 mb-2">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="text" class="form-control" name="jumlah" id="jumlah" autocomplete="off" oninput="formatAngka(this)">
                 </div>
                   <div class="col-sm-6 mb-2">
                     <label for="nama_user" class="form-label">Nama User</label>
                    <input type="text" class="form-control" id="nama_user" name="nama_user" autocomplete="off" value="{{Auth::user()->username}}" readonly>
                  </div>
                   <div class="col-sm-6 mb-2">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control"></textarea>
                   </div>
            </div>
          <button type="submit" class="btn btn-primary mr-2">Submit</button>
          <a href="/piutang.php" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
</div>
<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('node_modules/select2/dist/js/select2.min.js') }}"></script>
<script>
function formatAngka(input) {
    let nilai = input.value;

    // Menghapus semua karakter non-digit
    nilai = nilai.replace(/\D/g, '');

    // Membuat titik setiap 3 digit dari belakang
    nilai = nilai.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // Menetapkan nilai yang telah diformat ke input
    input.value = 'IDR ' + nilai;
}

// $('#nama_pelanggan').select2({
//         ajax: {
//             url: '/nama_pelangganSearch',
//             dataType: 'json',
//             delay: 250,
//             data: function (params) {
//                 return {
//                     q: params.term // istilah pencarian
//                 };
//             },
//             processResults: function (data) {
//                 return {
//                     results: $.map(data, function(obj) {
//                         return {
//                             id: obj.nama_pelanggan,
//                             text: obj.nama_pelanggan
//                             // alamat: obj.alamat
//                         };
//                     })
//                 };
//             },
//             cache: true
//         },
//         placeholder: 'Cari pelanggan',
//         minimumInputLength: 1,
//         templateResult: function (data) {
//             return data.text;
//         },
//         templateSelection: function (data) {
//             // $('#alamat').val(data.alamat); // Isi otomatis input alamat
//             return data.text;
//         }
//     });
    $('#cariKategori').select2({
    ajax: {
        url: '/searchKategoriPemasukan',
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
                        id: obj.kategori_id,
                        text: obj.name
                    };
                })
            };
        },
        cache: true
    },
    placeholder: 'Cari Kategori',
    minimumInputLength: 1,
    templateResult: function (data) {
        return data.text;
    },
    templateSelection: function (data) {
        if (data.id) {
            $('#subcategories_id').val(data.id); // Setel nilai input tersembunyi #subcategories_id
            $('#kategori').val(data.text); // Setel nilai input tersembunyi #subcategories_id
        }
        return data.text;
    }
    });


    // $('#nomor_nota').select2({
    //     ajax: {
    //         url: '/nomorNotaSearch',
    //         dataType: 'json',
    //         delay: 250,
    //         data: function (params) {
    //             return {
    //                 q: params.term // istilah pencarian
    //             };
    //         },
    //         processResults: function (data) {
    //             return {
    //                 results: $.map(data, function(obj) {
    //                     return {
    //                         id: obj.nomor_nota,
    //                         text: obj.nomor_nota,
    //                         nama: obj.nama_pelanggan,
    //                         jumlah: obj.total,
    //                     };
    //                 })
    //             };
    //         },
    //         cache: true
    //     },
    //     placeholder: 'Cari Nomor Nota',
    //     minimumInputLength: 1,
    //     templateResult: function (data) {
    //         $('#nomor_nota').val(data.id);

    //         return data.text;
    //     },
    //     templateSelection: function (data) {
    //         $('#nama_pelanggan').val(data.nama);
    //         $('#jumlah').val(data.jumlah);
    //         return data.text;
    //     }
    // });

    // $(document).ready(function() {
    //         // Ambil nomor bukti saat halaman dimuat
    //         fetchNomorBukti();

    //         // Fungsi untuk mengambil nomor bukti baru dari server
    //         function fetchNomorBukti() {
    //             $.ajax({
    //                 url: '/generate-nomor-bukti',
    //                 method: 'GET',
    //                 success: function(data) {
    //                     $('#nomor_bukti').val(data.nomor_bukti);
    //                 },
    //                 error: function() {
    //                     alert('Gagal mengambil nomor bukti.');
    //                 }
    //             });
    //         }
    //     });

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