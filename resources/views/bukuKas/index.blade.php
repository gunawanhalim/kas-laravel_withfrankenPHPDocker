@extends('layouts._header')
@section('title','Data Buku Kas')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        
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
        <div class="container mt-2 my-2">
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
        <h4 class="card-title">Halaman Buku Kas</h4>
        <hr>
        <button class="btn btn-inverse-primary btn-fw mb-4" id="addkategori">Tambah data</button>
        <table class="table table-bordered">

          <thead>
            <tr>
              <th> No </th>
              <th> Kategori </th>
              <th> Progress </th>
              <th> Amount </th>
              <th> Aksi </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($kas as $q)
            <tr>
                <td> {{$loop->iteration}} </td>
                <td> {{$q->nama_akun}} </td>
                <td> {{$q->tampil}} </td>
                <td>  </td>
                <td>  </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection