@extends('layouts._header')
@section('title', 'Piutang')

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Piutang</li>
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
            <h4 class="card-title">KAS PIUTANG </h4>
            <div style="overflow: hidden;">
                <a href="{{route('penjualan.add')}}" class="btn btn-primary mb-4" style="float: left;"> Tambah Penjualan </a>
                <a href="{{route('pelunasan.piutang')}}" class="btn btn-info mb-4" style="float: right;">Lihat Pelunasan</a>
            </div>      
            <div class="my-2">
                <div class="my-2">
                <form method="GET" action="{{ route('piutang.index') }}">
                    <label>Filter berdasarkan :</label>
                    <select class="form-control form-control-sm mb-3" style="width: 200px; height:30px;" aria-label=".form-select-sm example" name="category">
                        <option value="">All</option>
                        <option value="nomor_nota" @if(request('piutang') == 'nomor_nota') selected @endif>Nomor Nota</option>
                        <option value="nama_pelanggan" @if(request('piutang') == 'nama_pelanggan') selected @endif>Nama Pelanggan</option>
                        <option value="nama_akun" @if(request('piutang') == 'nama_akun') selected @endif>Nama Akun</option>
                        <option value="nomor_bukti" @if(request('piutang') == 'nomor_bukti') selected @endif>Nomor Bukti</option>
                        <option value="nama_user" @if(request('piutang') == 'nama_user') selected @endif>Nama User</option>
                        <option value="nama_user" @if(request('piutang') == 'nama_user') selected @endif>Nama Sales</option>
                    </select>
                    <div class="mb-2 my-2">
                        <input type="text" name="search" placeholder="Search Data..." value="{{ request('search') }}" class="form" autocomplete="off">
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-md btn-outline-success" style="width: 100px; height:30px;">Search</button>
                    </div>
                </form>
            </div>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th style="width: 10;">No</th>
                            <th style="width: 10;">Nomor Bukti</th>
                            <th style="width: 10;">Tanggal Bukti</th>
                            <th style="width: 10;">Tanggal Jatuh Tempo</th>
                            <th style="width: 10;">Nomor Nota</th>
                            <th style="width: 10;">Nama Pelanggan</th>
                            <th style="text-align: center; width:10;" >Jumlah</th>
                            <th style="text-align: center; width:10px;" >Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($piutang as $item)
                        @php
                            // Ambil tanggal dan waktu saat ini
                            $now = \Carbon\Carbon::now();
                            // Ambil tanggal jatuh tempo
                            $dueDate = \Carbon\Carbon::parse($item->jatuh_tempo);
                            // Periksa apakah tanggal jatuh tempo adalah hari ini
                            $isToday = $dueDate->isToday();
                            // Periksa apakah tanggal jatuh tempo adalah sehari sebelumnya
                            $isYesterday = $dueDate->isSameDay($now->copy()->subDay());
                        @endphp
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td><a href="/detailNomorBukti/{{$item->nomor_bukti}}">{{$item->nomor_bukti}}</a> </td>
                            <td>{{$item->tanggal_bukti}}</td>
                            @if ($isToday)
                            <td class="text-danger">
                                {{$item->jatuh_tempo}}

                            </td>
                            @elseif ($now)
                            <td class="colspan">
                                {{$item->jatuh_tempo}}
                            </td>
                            @endif
                            <td>{{$item->nomor_nota}}</td>
                            <td>{{$item->nama_pelanggan}}</td>
                            <td>Rp. {{ number_format($item->total_jumlah, 0, ',', '.') }}</td>
                            <td>
    
                                <div class="dropdown">
                                    <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="mdi mdi-table-edit"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuIconButton1">
                                      <h6 class="dropdown-header">Pengaturan</h6>
                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#detailModal" data-nomor-bukti="{{ $item->nomor_bukti }}">
                                        Detail
                                    </button>
                                    @if(Auth::user()->role == 'Admin')                    
                                    {{-- <a href="{{ route('piutang.edit', ['nomorBukti'=> $item->nomor_bukti]) }}" class="dropdown-item"> Edit</a> --}}
                                    <button type="button" class="dropdown-item" data-toggle="modal" data-target="#deleteModal" data-nomor-bukti="{{ $item->nomor_bukti }}">
                                        Hapus
                                    </button>
                                    @endif
                                    <a href="/pembayaran-add/{{$item->nomor_bukti}}" type="button" class="dropdown-item">
                                        Pembayaran
                                    </a>                                  
                                    {{-- <a class="dropdown-item" href="#">Something else here</a>
                                      <div class="dropdown-divider"></div>
                                      <a class="dropdown-item" href="#">Separated link</a> --}}
                                    </div>
                                  </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
        <div class="my-5">
            {{$piutang->links()}}
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
                yang berkaitan nomor: <span id="nomorToDelete"></span> terutama Kas Bank akan di hapus, apakah anda ingin menghapusnya? 
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
              url: '/debitDetail/' + nomorBukti,
              type: 'GET',
              dataType: 'json',
              success: function(data) {
                  $('#modalNomorBukti').text(data.nomor_bukti);
                  $('#modalTanggalBukti').text(data.tanggal_bukti);
                  $('#modalNomorNota').text(data.nomor_nota);
                                  // Set modalKategori text based on data.kategori
                    // if (data.kategori == 'Piutang') {
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

    // $('#pembayaranModal').on('shown.bs.modal', function (event) {
    //   var button = $(event.relatedTarget);
    //   var nomorBukti = button.data('nomor-bukti');
    //   getPembayaranDetail(nomorBukti);
    //   function getPembayaranDetail(nomorBukti) {
    //       $.ajax({
    //           url: '/pembayaranDetail/' + nomorBukti,
    //           type: 'GET',
    //           dataType: 'json',
    //           success: function(data) {
    //               $('#pembayaranModalNomorBukti').text(data.nomor_bukti);
    //               $('#pembayaranModalTanggalBukti').text(data.tanggal_bukti);
    //               $('#pembayaranModalNomorNota').text(data.nomor_nota);
    //                               // Set modalKategori text based on data.kategori
    //                 if (data.kategori == 1) {
    //                     $('#pembayaranModalKategori').text('Pemasukan');
    //                 } else if (data.kategori == 2) {
    //                     $('#pembayaranModalKategori').text('Pengeluaran');
    //                 } else {
    //                     $('#pembayaranModalKategori').text('Kategori Tidak Dikenali');
    //                 }
    //               $('#pembayaranModalNama').text(data.nama_pelanggan);
    //               $('#pembayaranModalNamaUser').text(data.nama_user);
    //               $('#pembayaranModalTanggalLog').text(data.tanggal_log);
    //               $('#pembayaranModalJumlah').text('Rp. ' + data.jumlah.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
    //           },
    //           error: function(xhr, status, error) {
    //               // Handle errors here
    //               console.error(error);
    //           }
    //       });
    //   }
    // });


      $('#deleteModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget); // Tombol yang memicu modal
          var nomor = button.data('nomor-bukti'); // Dapatkan data-nomor-bukti dari tombol
          var modal = $(this);
          var action = "{{ route('credit.destroy', ':nomor') }}";
          action = action.replace(':nomor', nomor);
          modal.find('#deleteForm').attr('action', action);
          
          // Tampilkan nomor di dalam modal
          modal.find('#nomorToDelete').text(nomor);
      });
  });


</script>
@endsection