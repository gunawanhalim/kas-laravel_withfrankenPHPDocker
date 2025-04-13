@extends('layouts._header')
@section('title', 'Detail Utang ' . $nama_akun)


@section('content')
    <!-- partial -->
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/credit.php">Kembali</a></li>
                <li class="breadcrumb-item active" aria-current="page">Utang</li>
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
            <h4 class="card-title">KAS CREDIT </h4>
            <a href="{{route('credit.add',['nama_akun'=> $nama_akun])}}" class="btn btn-primary mb-4"> Tambah data </a>
            <div class="my-2">
                <form method="GET" action="{{ route('credit.show', ['nama_akun' => $nama_akun]) }}">
                    <label>Filter berdasarkan :</label>
                    <select class="form-control form-control-sm mb-3" style="width: 200px; height:30px;" aria-label=".form-select-sm example" name="category">
                        <option value="">All</option>
                        <option value="nomor_nota" @if(request('category') == 'nomor_nota') selected @endif>Nomor Nota</option>
                        <option value="nama_pelanggan" @if(request('category') == 'nama_pelanggan') selected @endif>Nama Pelanggan</option>
                        <option value="nomor_bukti" @if(request('category') == 'nomor_bukti') selected @endif>Nomor Bukti</option>
                        <option value="nama_user" @if(request('category') == 'nama_user') selected @endif>Nama User</option>
                        <option value="nama_user" @if(request('category') == 'nama_user') selected @endif>Nama Sales</option>
                    </select>
                    <div class="mb-2 my-2">
                        <input type="text" name="search" placeholder="Search Data..." value="{{ request('search') }}" class="form" autocomplete="off">
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-md btn-outline-success" style="width: 100px; height:30px;">Search</button>
                    </div>
                </form>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 20px;">No</th>
                        <th style="width: 20px;">Nomor Bukti</th>
                        <th style="width: 20px;">Tanggal Bukti</th>
                        <th style="width: 20px;">Nama Pelanggan</th>
                        <th style="text-align: center; width:20px;" >Jumlah</th>
                        <th style="text-align: center; width:10px;" >Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($credit as $item)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$item->nomor_bukti}}</td>
                        <td>{{$item->tanggal_bukti}}</td>
                        <td>{{$item->nama_pelanggan}}</td>
                        <td>Rp. {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#detailModal" data-nomor-bukti="{{ $item->nomor_bukti }}">
                                Detail
                            </button>                            
                            <a href="{{ route('credit.edit', ['nomor_bukti'=> $item->nomor_bukti]) }}" class="btn btn-md btn-secondary"> Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-nomor-bukti="{{ $item->nomor_bukti }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="my-5">
            {{$credit->links()}}
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
  <!-- Modal Konfirmasi Penghapusan -->
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
    });
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