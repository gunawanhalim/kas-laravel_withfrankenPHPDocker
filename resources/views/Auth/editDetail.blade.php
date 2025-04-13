@extends('layouts._header')

@section('title', 'Edit User ' . $user->username)

@section('content')
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit User: {{$user->email}}</li>
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

    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit User</h4>
                <form action="{{ route('editUser.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nama User</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label">Role/Jabatan</label>
                        <select id="role" name="role" class="form-control">
                         <option value="Admin">Admin</option>
                         <option value="Manager">Manager</option>
                         <option value="Owner">Owner</option>
                     </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                          <input class="form-control" type="text" name="status" value="{{ $user->status_aktif == 1 ? 'Aktif' : 'Tidak Aktif' }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}" disabled>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" id="password" name="password" value="******" disabled>
                    </div>
                    <div class="form-group">
                            <label for="tanggal_login" class="form-label">Tanggal Login</label>
                            <input type="datetime-local" class="form-control" id="tanggal_login" name="tanggal_login" value="{{ $user->tanggal_login }}" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
<script src="{{ asset('/assets/js/jquery.min.js') }}"></script>   
@endsection
