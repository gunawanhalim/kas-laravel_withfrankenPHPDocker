@extends('layouts._header')
@section('title','Pelanggan Penjualan')

@section('content')
    <!-- partial -->
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pelanggan Pembelian</li>
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
            <h4 class="card-title">PELANGGAN PEMBELIAN</h4>
            <a href="#" class="btn btn-primary mb-5" data-toggle="modal" data-target="#addPelangganModal">Tambah Pelanggan</a>
            <form method="GET" action="{{ route('pelangganPenjualan.index') }}">
                <label>Filter berdasarkan :</label>
                <select class="form-control form-control-sm mb-3" style="width: 200px; height:30px;" aria-label=".form-select-sm example" name="category">
                    <option value="">All</option>
                    <option value="nama_pelanggan" @if(request('category') == 'nama_pelanggan') selected @endif>Nama Pelanggan</option>
                    <option value="alamat" @if(request('category') == 'alamat') selected @endif>Alamat</option>
                </select>
                <div class="mb-2 my-2">
                    <input type="text" name="search" placeholder="Search Data..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-md btn-outline-success" style="width: 100px; height:30px;">Search</button>
                </div>
            </form>
            </div>
            
            <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px;"> No </th>
                    <th style="width: 10px;"> Nama Pelanggan </th>
                    <th style="width: 10px;"> Alamat </th>
                    <th style="width: 2%"> Edit </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($pelanggan as $qp)
                    <tr>
                      <td> {{$loop->iteration}} </td>
                      <td> {{$qp->nama_pelanggan}} </td>
                      <td> {{$qp->alamat}} </td>
                      <td> 
                        <a href="#" class="btn btn-outline-info btn-icon-text mr-2 mb-2" data-toggle="modal" data-target="#addPelangganModal" data-item-id="{{$qp->id}}" data-item-nama-pelanggan="{{$qp->nama_pelanggan}}" data-item-alamat="{{$qp->alamat}}"> <i class="mdi mdi mdi mdi-tooltip-edit btn-icon-prepend"></i></a>
                        <form action="{{ route('deletePelangganPembelian.destroy', $qp->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-outline-danger btn-icon-text mr-2" type="submit">
                            <i class="mdi mdi mdi-delete-forever btn-icon-prepend"></i>
                          </button>
                      </form>
                      </td>
                    </tr>
                  @endforeach

                </tbody>
              </table>
        </div>
        <div class="my-5">
            {{ $pelanggan->appends(request()->except('page'))->links() }}
        </div>
    </div>
 <!-- Modal Pelanggan -->
 <div class="modal fade" id="addPelangganModal" tabindex="-1" aria-labelledby="addPelangganModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPelangganModalLabel">Tambah Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form content goes here -->
                <form id="addPelangganForm" action="{{route('addPelangganPembelian.store')}}" method="post">
                    @csrf
                    <input type="hidden" id="pelangganId" name="pelangganId">
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" cols="50" rows="5"></textarea>
                      </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
  </div>
  <!-- End Modal Pelanggan -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
       // Ajax Store dan Edit Pelanggan
       $(document).ready(function() {
        $('#addPelangganModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('item-id'); // Extract info from data-* attributes
            var namaPelanggan = button.data('item-nama-pelanggan');
            var alamat = button.data('item-alamat');

            var modal = $(this);
            var formAction = "{{route('addPelangganPembelian.store')}}";

            if (id) {
                modal.find('.modal-title').text('Edit Pelanggan');
                formAction = "{{route('editPelangganPembelian.update', ':id')}}".replace(':id', id);
                modal.find('#pelangganId').val(id);
                modal.find('#nama_pelanggan').val(namaPelanggan);
                modal.find('#alamat').val(alamat);
            } else {
                modal.find('.modal-title').text('Tambah Pelanggan');
                modal.find('#pelangganId').val('');
                modal.find('#nama_pelanggan').val('');
                modal.find('#alamat').val('');
            }

            modal.find('#addPelangganForm').attr('action', formAction);
        });
    });
    // End store dan edit
</script>
@endsection