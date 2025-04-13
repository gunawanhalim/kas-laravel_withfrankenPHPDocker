@extends('layouts._header')
@section('title','Tambah Penjualan')

@section('content')
    <!-- partial -->
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penjualan</li>
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
            $today = date('Y-m-d H:i:s');
            // Ubah string tanggal menjadi objek DateTime
            $date = new DateTime($today);

            // Tambahkan satu bulan ke tanggal
            $date->modify('+1 month');

            // Format tanggal menjadi string
            $due_date = $date->format('Y-m-d H:i:s');
        @endphp
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title">TAMBAH PENJUALAN</h4>
                <form class="forms-sample" action="{{route('penjualan.store')}}" method="post">
                    @csrf
                    <div class="form-group row">
                        <div class="form-group col-sm-12 mb-2">
                            <label for="nomor_nota">Nomor Nota</label>
                            <input type="text" id="nomor_nota" name="nomor_nota" class="form-control" value="{{ old('nomor_nota') }}">
                            <input type="checkbox" id="generate_nomor" name="generate_nomor" checked>
                            <label for="generate_nomor">Aktifkan Nomor Nota</label>
                        </div>
                        <div class="col-sm-6 mb-2">
                           <label for="tanggal_nota" class="form-label">Tanggal Nota</label>
                           <input type="datetime-local" class="form-control" name="tanggal_nota" id="tanggal_nota" placeholder="" value="{{$today}}">
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label for="tanggal_nota" class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="datetime-local" class="form-control" name="jatuh_tempo" id="jatuh_tempo" placeholder="" value="{{$due_date}}">
                         </div>
                        <div class="col-sm-6 mb-2">
                           <label for="nama_pelanggan" class="form-label">Nama Pelanggan</label>
                            <select id="nama_pelanggan" name="nama_pelanggan" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                        {{-- <div class="col-sm-6 mb-2">
                            <label for="kategori" class="form-label">Kategori</label>
                             <select id="cariKategori" name="cariKategori" class="form-control">
                                 <option value=""></option>
                             </select>
                             <input type="hidden" class="form-control" name="kategori" id="kategori" value="">
                         </div> --}}
                         <div class="col-sm-6 mb-2">
                            <label for="kategori" class="form-label">Kategori Supplier</label>
                             <select id="kategori" name="kategori" class="form-control">
                                 @foreach ($supplier as $item)
                                 <option value="{{$item->name}}">{{$item->name}}</option>
                             @endforeach
                         </select>
                             </select>
                         </div>
                        <div class="col-sm-6 mb-2">
                            <label for="alamat" class="form-label">Alamat</label>
                           <input type="text" class="form-control" id="alamat" name="alamat" placeholder="" autocomplete="off">
                         </div>
                         <div class="col-sm-6 mb-2">
                            <label for="nama_sales" class="form-label">Nama Sales</label>
                           <input type="text" class="form-control" name="nama_sales" id="nama_sales" autocomplete="off">
                         </div>
                         <div class="col-sm-6 mb-2">
                           <label for="total" class="form-label">Jumlah</label>
                           <input type="text" class="form-control" name="total" id="total" autocomplete="off" oninput="formatAngka(this)">
                         </div>
                         <div class="col-sm-6 mb-2">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" cols="30" rows="10" class="form-control"></textarea>
                           </div>
                         <div class="col-sm-6 mb-2">
                            {{-- <label for="nama_user" class="form-label">Nama User</label> --}}
                            <input type="hidden" class="form-control" id="nama_user" autocomplete="off" value="{{Auth::user()->username}}" readonly>
                            {{-- <input type="text" class="form-control" name="subcategories_id" id="subcategories_id" autocomplete="off" hidden> --}}

                          </div>
                    </div>

                  <button type="submit" class="btn btn-primary mr-2">Submit</button>
                  <a href="/penjualan" class="btn btn-light">Cancel</a>
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
        if (data.loading) {
            return data.text;
        }
        var $container = $('<div></div>');
        $container.text(data.text + ' - ' + data.alamat);
        return $container;
    },
        templateSelection: function (data) {
            $('#alamat').val(data.alamat); // Isi otomatis input alamat
            return data.text;
        }
    });
    // All Kategori
    // $('#cariKategori').select2({
    // ajax: {
    //     url: '/searchKategori',
    //     dataType: 'json',
    //     delay: 250,
    //     data: function (params) {
    //         return {
    //             q: params.term // istilah pencarian
    //         };
    //     },
    //     processResults: function (data) {
    //         return {
    //             results: $.map(data, function(obj) {
    //                 return {
    //                     id: obj.kategori_id,
    //                     text: obj.name
    //                 };
    //             })
    //         };
    //     },
    //     cache: true
    // },
    // placeholder: 'Cari Kategori',
    // minimumInputLength: 1,
    // templateResult: function (data) {
    //     return data.text;
    // },
    // templateSelection: function (data) {
    //     if (data.id) {
    //         $('#subcategories_id').val(data.id); // Setel nilai input tersembunyi #subcategories_id
    //         $('#kategori').val(data.text); // Setel nilai input tersembunyi #subcategories_id
    //     }
    //     return data.text;
    // }
    // });
$('#cariKategori').select2({
    ajax: {
        url: '/searchKategoriPengeluaran',
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

document.addEventListener('DOMContentLoaded', function() {
    const nomorNotaInput = document.getElementById('nomor_nota');
    const generateCheckbox = document.getElementById('generate_nomor');

    function updateNomorNota() {
        if (generateCheckbox.checked) {
            fetch('/addPenjualan/generate-nomor-nota')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    nomorNotaInput.value = data.nomor_nota;
                    nomorNotaInput.setAttribute('readonly', 'readonly'); // Set readonly ketika generate dicentang
                })
                .catch(error => {
                    console.error('Error fetching nomor nota:', error);
                });
        } else {
            nomorNotaInput.value = '';  // Kosongkan input jika checkbox tidak dicentang
            nomorNotaInput.removeAttribute('readonly'); // Hapus readonly ketika generate tidak dicentang
        }
    }

    generateCheckbox.addEventListener('change', updateNomorNota);

    // Inisialisasi saat halaman dimuat
    updateNomorNota();
});
</script>
@endsection