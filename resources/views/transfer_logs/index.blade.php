@extends('layouts._header')
@section('title','History Transfer')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- partial -->
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Histoy Transfer</li>
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
            <h4 class="card-title">History Transfer</h4>
            <a href="{{route('penjualan.add')}}" class="btn btn-primary mb-4"> Tambah data </a>            <div class="my-2">
                <form method="GET" action="{{ route('penjualan.index') }}">
                    <label>Filter berdasarkan :</label>
                    <select class="form-control form-control-sm mb-3" style="width: 200px; height:30px;" aria-label=".form-select-sm example" name="category">
                        <option value="">All</option>
                        <option value="nomor_nota" @if(request('transfer_logs') == 'nomor_nota') selected @endif>Nomor Nota</option>
                        <option value="nama_pelanggan" @if(request('transfer_logs') == 'nama_pelanggan') selected @endif>Nama Pelanggan</option>
                        <option value="nama_sales" @if(request('transfer_logs') == 'nama_sales') selected @endif>Nama Sales</option>
                        <option value="nama_user" @if(request('transfer_logs') == 'nama_user') selected @endif>Nama User</option>
                    </select>
                    <div class="mb-2 my-2">
                        <input type="text" name="search" placeholder="Search Data..." value="{{ request('search') }}">
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-md btn-outline-success" style="width: 100px; height:30px;">Search</button>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <button id="delete-selected-items" class="btn btn-danger">Hapus yang Dipilih</button>
            </div>
            <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 20px;">No</th>
                        <th style="width: 20px;">Dari Akun</th>
                        <th style="width: 20px;">Ke Akun</th>
                        <th style="text-align: center; width:20px;" >Tanggal Transfer</th>
                        <th style="text-align: center; width:20px;" >Keterangan</th>
                        <th style="text-align: center; width:20px;" >Jumlah</th>
                        {{-- <th style="text-align: center; width:10px;" >Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transfer as $item)
                    <tr>
                        <td>
                            <div id="item-{{ $item->id }}">
                                <input type="checkbox" class="item-checkbox" value="{{ $item->id }}">
                                <span>{{ $loop->iteration }}</span>
                            </div>
                        </td>  
                        <td>{{$item->fromAccount->nama_akun}}</td>
                        <td>{{$item->toAccount->nama_akun}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->description}}</td>
                        <td>Rp. {{ number_format($item->amount, 0, ',', '.') }}</td>
                        {{-- <td>
                            <a href="{{ route('penjualan.detail', ['nomor_nota' => $item->nomor_nota]) }}" class="btn btn-md btn-info">Detail</a>
                            <a href="{{ route('penjualan.edit', ['nomor_nota'=> $item->nomor_nota]) }}" class="btn btn-md btn-primary"> Edit</a>
                            <a href="{{ route('penjualan.delete', ['nomor_nota'=> $item->nomor_nota] ) }}" class="btn btn-md btn-danger"> Delete</a>
                        </td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
        <div class="my-5">
            {{ $transfer->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>
<script src="../assets/js/toastify-js.js"></script>
<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>

<script>
    $('#delete-selected-items').click(function (e) {
    e.preventDefault();
    var selectedItems = $('.item-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    $.ajax({
    url: '/items/delete',
    type: 'GET',
    dataType: 'json',
    data: {
        ids: selectedItems,
    },
    success: function (response) {
        // Tampilkan pesan sukses kepada pengguna menggunakan Toastify
        Toastify({
            text: 'History berhasil dihapus.',
            duration: 3000,
            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
        }).showToast();
        // Reload halaman setelah beberapa detik
        setTimeout(function(){
            location.reload();
        }, 3000);
    },

    error: function (xhr, status, error) {
         // Gagal menghapus, tangani kesalahan jika diperlukan
         console.log('Failed to delete selected items.');
        // Tampilkan pesan kesalahan kepada pengguna
        Toastify({
            text: 'Gagal menghapus History.',
            duration: 3000,
            backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
        }).showToast();
    }
});

});
</script>
@endsection