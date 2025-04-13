@extends('layouts._header')
@section('title', 'Piutang '. $nomor_bukti)

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
            transform: scale(0.65); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }
    @media (min-width: 900px) {
        .table-container {
            transform: scale(0.70); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }
    @media (min-width: 1050px) {
        .table-container {
            transform: scale(0.70); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }

    @media (min-width: 1340px) {
        .table-container {
            transform: scale(0.85); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }
    @media (min-width: 1400px) {
        .table-container {
            transform: scale(0.99); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }

</style>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/piutang.php">Kembali ke piutang</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Penjualan</li>
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
            <h4 class="card-title">KAS PEMBAYARAAN {{$nomor_bukti}} </h4>
            <div class="table-container">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width: 10;">No</th>
                        <th style="width: 10;">Nomor Bukti</th>
                        <th style="width: 10;">Tanggal Bukti</th>
                        <th style="width: 10;">Nomor Nota</th>
                        <th style="width: 10px;">Nama Akun</th>
                        <th style="width: 10;">Nama Pelanggan</th>
                        <th style="width: 10;">Kategori</th>
                        <th style="text-align: center; width:10;" >Jumlah</th>
                        <th style="text-align: center; width:10;" >Saldo</th>
                        <th style="text-align: center; width:10px;" >Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $saldo = 0; @endphp
                    @foreach ($pembayaran as $index => $item)
                    @php
                        if($item->kategori == "Piutang") {
                            $saldo -= $item->jumlah;
                        } else {
                            $saldo += $item->jumlah;
                        }
                    @endphp
                    <tr class="{{ $item->jumlah > 0 ? 'table-success' : 'table-danger' }}">
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nomor_bukti}}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_bukti)->format('d-m-Y') }}</td>
                        <td>{{$item->nomor_nota}}</td>
                        {{-- <td>{{$item->kategoriSupplier->name}}</td> --}}
                        <td>{{$item->nama_akun}}</td>
                        <td>{{$item->nama_pelanggan}}</td>
                        <td>{{$item->kategori}}</td>
                        <td>Rp. {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>Rp. {{ number_format($saldo, 0, ',', '.') }}</td>
                        <td>
                              <button type="button" class="dropdown-item" data-toggle="modal" data-target="#detailModal" data-nomor-bukti="{{ $item->id }}">
                                  Detail
                              </button>
                              @if(Auth::user()->role == "Owner" || Auth::user()->role == "Manager")                    
                                @if($index > 0) <!-- Disable edit button for the first item -->
                                    <a href="{{ route('pembayaran.edit', ['id'=> $item->id]) }}" class="dropdown-item"> Edit</a>
                                @else
                                    <button type="button" class="dropdown-item" readonly title="Silahkan edit di Nota Penjualan">Edit</button>
                                @endif
                              <button type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteModal" data-nomor-bukti="{{ $item->id }}">
                                  Hapus
                              </button>
                              @endif  
                            {{-- <div class="dropdown">
                                <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="mdi mdi-table-edit"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuIconButton1">
                                  <h6 class="dropdown-header">Pengaturan</h6>
                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#detailModal" data-nomor-bukti="{{ $item->id }}">
                                    Detail
                                </button>
                                @if(Auth::user()->role == 'Admin')                    
                                <a href="{{ route('pembayaran.edit', ['id'=> $item->id]) }}" class="dropdown-item"> Edit</a>
                                <button type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteModal" data-nomor-bukti="{{ $item->id }}">
                                    Hapus
                                </button>
                                @endif                               
                                {{-- <a class="dropdown-item" href="#">Something else here</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Separated link</a> 
                                </div>
                              </div> --}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <div class="my-5">
            {{$pembayaran->links()}}
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
              url: '/pembayaran-detail/' + nomorBukti,
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
          var action = "{{ route('pembayaran.destroy', ':id') }}";
          action = action.replace(':id', id);
          modal.find('#deleteForm').attr('action', action);
          
          // Tampilkan nomor di dalam modal
          modal.find('#nomorToDelete').text(id);
      });
  });


</script>
@endsection