@extends('layouts._header')
@section('title','Pembelian')

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
            transform: scale(0.82); /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
        }
    }
</style>
    <!-- partial -->
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pembelian</li>
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
            <h4 class="card-title">KAS PEMBELIAN</h4>
            <a href="{{route('pembelian.add')}}" class="btn btn-primary mb-4"> Tambah Pembelian </a>            
            <div class="my-2">
                <form method="GET" action="{{ route('pembelian.index') }}">
                    <label>Filter berdasarkan :</label>
                    <select class="form-control form-control-sm mb-3" style="width: 200px; height:30px;" aria-label=".form-select-sm example" name="category">
                        <option value="">All</option>
                        <option value="nomor_nota" @if(request('pembelian') == 'nomor_nota') selected @endif>Nomor Nota</option>
                        <option value="nama_pelanggan" @if(request('pembelian') == 'nama_pelanggan') selected @endif>Nama Pelanggan</option>
                        <option value="nama_sales" @if(request('pembelian') == 'nama_sales') selected @endif>Nama Sales</option>
                        <option value="nama_user" @if(request('pembelian') == 'nama_user') selected @endif>Nama User</option>
                    </select>
                    <div class="mb-2 my-2">
                        <input type="text" name="search" placeholder="Search Data..." value="{{ request('search') }}">
                        <input type="date" name="start_date" value="{{ request('start_date') }}">
                        <input type="date" name="end_date" value="{{ request('end_date') }}">
                        <button type="submit" class="btn btn-md btn-outline-success" style="width: 100px; height:30px;">Search</button>
                    </div>
                </form>
            </div>
            <div class ="table-container">
            <table class="table table-bordered mt-2">
                <thead>
                    <tr>
                        <th style="width: 20px;">No</th>
                        <th style="width: 20px;">Nomor Nota</th>
                        {{-- <th style="width: 20px;">Nama Pelanggan</th> --}}
                        <th style="text-align: center; width:20px;" >Nama Sales</th>
                        <th style="text-align: center; width:20px;" >Tanggal Nota</th>
                        <th style="text-align: center; width:20px;" >Tanggal Jatuh Tempo</th>
                        <th style="text-align: center; width:20px;" >Jumlah</th>
                        <th style="text-align: center; width:10px;" >Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $item)
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
                        <td>{{$item->nomor_nota}}</td>
                        {{-- <td>{{$item->nama_pelanggan}}</td> --}}
                        <td>{{$item->nama_sales}}</td>
                        <td>{{$item->tanggal_nota}}</td>
                        @if ($isToday)
                        <td class="text-danger">
                            Jatuh tempo hari ini! {{$item->jatuh_tempo}}

                        </td>
                        @elseif ($now)
                        <td class="colspan">
                            {{$item->jatuh_tempo}}
                        </td>
                        @endif
                        <td>Rp. {{ number_format($item->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('pembelian.detail', ['nomor_nota' => $item->nomor_nota]) }}" class="btn btn-md btn-info">Detail</a>
                            @if (Auth::user()->role == "Owner" || Auth::user()->role == "Manager" )
                            <a href="{{ route('pembelian.edit', ['nomor_nota'=> $item->nomor_nota]) }}" class="btn btn-md btn-primary"> Edit</a>
                            <a href="{{ route('pembelian.delete', ['nomor_nota'=> $item->nomor_nota] ) }}" class="btn btn-md btn-danger"> Delete</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <div class="my-5">
            {{ $sales->appends(request()->except('page'))->links() }}
        </div>
    </div>
</div>

@endsection