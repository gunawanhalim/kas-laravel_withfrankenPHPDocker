@extends('layouts._header')
@section('title', 'Pelunasan Utang ')

@section('content')
<style>
    .table-container {
        transform-origin: 0 0; /* Menetapkan titik awal transformasi */
    }

    @media (min-width: 425px) {
        .table-container {
            transform: scale(0.6); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }

    @media (min-width: 768px) {
        .table-container {
            transform: scale(0.7); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }

    @media (min-width: 1024px) {
        .table-container {
            transform: scale(0.7); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }

    @media (min-width: 1340px) {
        .table-container {
            transform: scale(0.9); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }
</style>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/utang.php">Kembali ke utang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pelunasan Utang</li>
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
            <h4 class="card-title">PELUNASAN KATEGORI HUTANG </h4>
            <div class="my-2">
                <form method="GET" action="{{ route('pelunasan.utang') }}">
                    <label>Filter berdasarkan :</label>
                    <select class="form-control form-control-sm mb-3" style="width: 200px; height:30px;" aria-label=".form-select-sm example" name="category">
                        <option value="">All</option>
                        <option value="nomor_nota" @if(request('category') == 'nomor_nota') selected @endif>Nomor Nota</option>
                        <option value="nomor_bukti" @if(request('category') == 'nomor_bukti') selected @endif>Nomor Bukti</option>
                        <option value="nama_akun" @if(request('category') == 'nama_akun') selected @endif>Nama Akun</option>
                        <option value="nama_user" @if(request('category') == 'nama_user') selected @endif>Nama User</option>
                    </select>
                    <div class="mb-2 my-2">
                        <input type="text" name="search" placeholder="Search Data..." value="{{ request('search') }}" class="form" autocomplete="off">
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-md btn-outline-success" style="width: 100px; height:30px;">Search</button>
                    </div>
                </form>
            </div>
            <div class="table-container">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th style="width: 10;">No</th>
                            <th style="width: 10;">Nomor Bukti</th>
                            <th style="width: 10;">Tanggal Bukti</th>
                            <th style="width: 10px;">Jatuh Tempo</th>
                            <th style="width: 10;">Nomor Nota</th>
                            {{-- <th style="width: 10;">Kategori</th> --}}
                            <th style="text-align: center; width:10;" >Status</th>
                            {{-- <th style="text-align: center; width:10;" >Saldo</th> --}}
                            {{-- <th style="text-align: center; width:10px;" >Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $index = 1; // Variabel untuk nomor urut baris
                    @endphp
                    
                    @if ($pelunasan->count() > 0)
                        @php
                            $i = 0;
                            $saldo = 0;
                        @endphp
                        @foreach ($pelunasan as $item)
                            @php
                                $jumlah = $item->jumlah;
                                if ($i == 0) {
                                    $saldo = $jumlah;
                                } else {
                                    $saldo += $item->jumlah;
                                }
                                $i = $i + 1;
                            @endphp
                                <tr class="{{ $item->jumlah < 0 ? 'table-danger' : 'table-success' }}">
                                    <td>{{ $index }}</td> <!-- Tampilkan nomor urut -->
                                    <td>{{ $item->nomor_bukti }}</td>
                                    <td>{{ $item->tanggal_bukti }}</td>
                                    <td>{{ $item->jatuh_tempo }}</td>
                                    <td>{{ $item->nomor_nota }}</td>
                                    {{-- <td>{{ $item->kategori }}</td> --}}
                                    <td>
                                        <strong>Lunas</strong>   
                                    </td>
                                    {{-- <!-- <td>Rp. {{ number_format($saldo, 0, ',', '.') }}</td> --> --}}
                                    {{-- <strong>Lunas</strong> --}}
                                    <td> 
                                        <a href="/pinjaman-kategoriDetail/{{$item->nomor_bukti}}" type="button" style="width: 50px;" class="btn btn-sm btn-outline-dark btn-fw">
                                            Detail <i class="mdi mdi-file-check btn-icon-append"></i>
                                        </a>                                  
                                    </td>
                                </tr>
                                @php
                                    $index++; // Update index setelah setiap baris yang ditampilkan
                                @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                        </tr>
                    @endif                    
                </table>
            </div>
        </div>
        <div class="my-5">
            {{$pelunasan->links()}}
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel">Detail Transaksi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p><strong>Nomor Bukti:</strong> <span id="modalNomorBukti"></span></p>
            <p><strong>Tanggal Bukti:</strong> <span id="modalTanggalBukti"></span></p>
            <p><strong>Nomor Nota:</strong> <span id="modalNomorNota"></span></p>
            <p><strong>Jumlah:</strong> <span id="modalJumlah"></span></p>
            <p><strong>Kategori:</strong> <span id="modalKategori"></span></p>
            <p><strong>Nama Pelanggan:</strong> <span id="modalNama"></span></p>
            <p><strong>Nama User:</strong> <span id="modalNamaUser"></span></p>
            <p><strong>Tanggal Log:</strong> <span id="modalTanggalLog"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <a type="button" class="btn btn-danger" href="/exportDetailDebit">Export</a>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah anda yakin ingin menghapus ID: <span id="nomorToDelete"></span>?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
<script>
  $(document).ready(function() {
      $('#detailModal').on('shown.bs.modal', function (event) {
      var button = $(event.relatedTarget);
      var nomorBukti = button.data('nomor-bukti');
      getTransactionDetails(nomorBukti);
      // Ajax request to get transaction details
      function getTransactionDetails(nomorBukti) {
          $.ajax({
              url: '/pinjaman-detail/' + nomorBukti,
              type: 'GET',
              dataType: 'json',
              success: function(data) {
                  $('#modalNomorBukti').text(data.nomor_bukti);
                  $('#modalTanggalBukti').text(data.tanggal_bukti);
                  $('#modalNomorNota').text(data.nomor_nota);
                    //               // Set modalKategori text based on data.kategori
                    // if (data.kategori == 1) {
                    //     $('#modalKategori').text('Pemasukan');
                    // } else if (data.kategori == 2) {
                    //     $('#modalKategori').text('Pengeluaran');
                    // } else {
                    //     $('#modalKategori').text('Kategori Tidak Dikenali');
                    // }
                  $('#modalKategori').text(data.kategori);
                  $('#modalNama').text(data.nama_pelanggan);
                  $('#modalNamaUser').text(data.nama_user);
                  $('#modalTanggalLog').text(data.tanggal_log);
                  $('#modalJumlah').text('Rp. ' + data.jumlah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
              },
              error: function(xhr, status, error) {
                  // Handle errors here
                  console.error(error);
              }
          });
      }
    });

      $('#deleteModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); // Tombol yang memicu modal
          var id = button.data('nomor-bukti'); // Dapatkan data-nomor-bukti dari tombol
          var modal = $(this);
          var action = "{{ route('pinjaman.destroy', ':id') }}";
          action = action.replace(':id', id);
          modal.find('#deleteForm').attr('action', action);
          
          // Tampilkan nomor di dalam modal
          modal.find('#nomorToDelete').text(id);
      });
  });


</script>
@endsection