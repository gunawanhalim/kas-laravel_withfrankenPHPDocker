@extends('layouts._header')
@section('title', 'Beranda')

@section('content')
    <!-- partial -->
    <div class="my-2">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container mt-4">
            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            <!-- Success Message -->
            @if (session('message'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-sm-flex justify-content-between align-items-center transaparent-tab-border {">
                <!-- Tab List -->
                <ul class="nav nav-tabs tab-transparent" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="business-tab" data-toggle="tab" href="#business-1" role="tab"
                            aria-selected="true">Kas</a>
                    </li>
                </ul>
            </div>
            <!-- End Tab List -->
            <div class="tab-content tab-transparent-content">
                <!-- Tab Analisis -->
                <div class="tab-pane fade show" id="performance-1" role="tabpanel" aria-labelledby="performance-tab">
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h4 class="card-title mb-0">Recent Activity</h4>
                                                <div class="dropdown dropdown-arrow-none">
                                                    <button class="btn p-0 text-dark dropdown-toggle" type="button"
                                                        id="dropdownMenuIconButton1" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false">
                                                        <i class="mdi mdi-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right"
                                                        aria-labelledby="dropdownMenuIconButton1">
                                                        <h6 class="dropdown-header">Settings</h6>
                                                        <a class="dropdown-item" href="#">Action</a>
                                                        <a class="dropdown-item" href="#">Another action</a>
                                                        <a class="dropdown-item" href="#">Something else here</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Separated link</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-sm-4 grid-margin  grid-margin-lg-0">
                                            <div class="wrapper pb-5 border-bottom">
                                                <div
                                                    class="text-wrapper d-flex align-items-center justify-content-between mb-2">
                                                    <p class="mb-0 text-dark">Total Profit</p>
                                                    <span class="text-success"><i class="mdi mdi-arrow-up"></i>2.95%</span>
                                                </div>
                                                <h3 class="mb-0 text-dark font-weight-bold">$ 92556</h3>
                                                <canvas id="total-profit"></canvas>
                                            </div>
                                            <div class="wrapper pt-5">
                                                <div
                                                    class="text-wrapper d-flex align-items-center justify-content-between mb-2">
                                                    <p class="mb-0 text-dark">Expenses</p>
                                                    <span class="text-success"><i class="mdi mdi-arrow-up"></i>52.95%</span>
                                                </div>
                                                <h3 class="mb-4 text-dark font-weight-bold">$ 59565</h3>
                                                <canvas id="total-expences"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-sm-8 grid-margin  grid-margin-lg-0">
                                            <div class="pl-0 pl-lg-4 ">
                                                <div class="d-xl-flex justify-content-between align-items-center mb-2">
                                                    <div class="d-lg-flex align-items-center mb-lg-2 mb-xl-0">
                                                        <h3 class="text-dark font-weight-bold mr-2 mb-0">Devices sales</h3>
                                                        <h5 class="mb-0">( growth 62% )</h5>
                                                    </div>
                                                    <div class="d-lg-flex">
                                                        <p class="mr-2 mb-0">Timezone:</p>
                                                        <p class="text-dark font-weight-bold mb-0">GMT-0400 Eastern Delight
                                                            Time</p>
                                                    </div>
                                                </div>
                                                <div class="graph-custom-legend clearfix" id="device-sales-legend"></div>
                                                <canvas id="device-sales"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Tab Analisis-->

                <!-- Tab Buku Kas -->
                <div class="tab-pane fade show active" id="business-1" role="tabpanel" aria-labelledby="business-tab">
                    <div class="col-sm-12  grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                {{-- Contoh Javascript Permission Role --}}
                                {{-- @php
                                    $rolePermission = Auth::user()->rolePermission;
                                    $user = json_decode($rolePermission->t_kendaraan_in, true);
                                    $userBatasPengguna = json_decode($rolePermission->batas_pengguna, true);
                                @endphp
                                @if ($user['Add'] == "1")
                                @endif --}}
                                @if (Auth::user()->role == "Owner" || Auth::user()->role == "Manager" )
                                <div class="d-xl-flex justify-content-between mb-2">
                                    <a href="#" class="btn btn-primary" data-toggle="modal"
                                        data-target="#kasModal">Tambah Buku Kas</a>
                                </div>
                                @endif
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px;"> No </th>
                                            <th style="width: 10px;"> Nama Akun </th>
                                            <th style="width: 10px;"> Tampilkan </th>
                                            <th style="width: 10px; text-align:center;"> Edit </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($akun_kas as $q)
                                            <tr>
                                                <td> {{ $loop->iteration }} </td>
                                                <td><a href="/akun_kas/{{ $q->nama_akun }}"> {{ $q->nama_akun }} </a></td>
                                                <td> {{ $q->tampil }} </td>
                                                <td>
                                                    <div class="row">
                                                        <a href="#" class="btn btn-outline-info btn-icon-text mr-2"
                                                            data-toggle="modal" data-target="#kasModal"
                                                            data-item-id="{{ $q->id }}"
                                                            data-item-nama-akun="{{ $q->nama_akun }}"
                                                            data-item-tampil="{{ $q->tampil }}">
                                                            <i
                                                                class="mdi mdi mdi mdi-tooltip-edit btn-icon-prepend"></i></a>
                                                        <form action="/deleteKas/{{ $q->id }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-outline-danger btn-icon-text">
                                                                <i
                                                                    class="mdi mdi mdi-delete-forever btn-icon-prepend"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div class="my-5">
                                {{ $akun_kas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab End Buku Kas -->

                <!-- Tab Pelanggan -->
                <div class="tab-pane fade" id="pelanggan-1" role="tabpanel" aria-labelledby="pelanggan-tab">
                    <div class="col-sm-12  grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-xl-flex justify-content-between mb-2">
                                    <!-- Button trigger modal -->
                                    <a href="#" class="btn btn-primary mb-5" data-toggle="modal"
                                        data-target="#addPelangganModal">Tambah Pelanggan</a>
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
                                                <td> {{ $loop->iteration }} </td>
                                                <td> {{ $qp->nama_pelanggan }} </td>
                                                <td> {{ $qp->alamat }} </td>
                                                <td>
                                                    <a href="#" class="btn btn-outline-info btn-icon-text mr-2 mb-2"
                                                        data-toggle="modal" data-target="#addPelangganModal"
                                                        data-item-id="{{ $qp->id }}"
                                                        data-item-nama-pelanggan="{{ $qp->nama_pelanggan }}"
                                                        data-item-alamat="{{ $qp->alamat }}"> <i
                                                            class="mdi mdi mdi mdi-tooltip-edit btn-icon-prepend"></i></a>
                                                    <form action="{{ route('deletePelanggan.destroy', $qp->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-outline-danger btn-icon-text mr-2"
                                                            type="submit">
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
                                {{ $pelanggan->links() }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab End Pelanggan -->

                @if (Auth::user()->role == 'Admin')
                    <!-- Tab User -->
                    <div class="tab-pane fade" id="user-1" role="tabpanel" aria-labelledby="user-tab">
                        <div class="col-sm-12  grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-xl-flex justify-content-between mb-2">
                                        <!-- Button trigger modal -->
                                        <a href="#" class="btn btn-primary mb-5" data-toggle="modal"
                                            data-target="#addUserModal">Tambah User</a>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th> No </th>
                                                <th> Nama </th>
                                                <th> Username </th>
                                                <th> Email </th>
                                                <th> Tanggal Login </th>
                                                <th> Edit </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $qu)
                                                <tr>
                                                    <td> {{ $loop->iteration }} </td>
                                                    <td> {{ $qu->name }} </td>
                                                    <td> {{ $qu->username }} </td>
                                                    <td> {{ $qu->email }} </td>
                                                    <td> {{ $qu->tanggal_login }} </td>
                                                    <td>
                                                        <a href="#"
                                                            class="btn btn-outline-info btn-icon-text mr-2 mb-2"
                                                            data-toggle="modal" data-target="#addUserModal"
                                                            data-item-id="{{ $qu->id }}"
                                                            data-item-nama="{{ $qu->name }}"
                                                            data-item-username="{{ $qu->username }}"
                                                            data-item-email="{{ $qu->email }}"
                                                            data-item-password="{{ $qu->password }}"
                                                            data-item-role="{{ $qu->role }}"> <i
                                                                class="mdi mdi mdi mdi-tooltip-edit btn-icon-prepend"></i></a>
                                                        <form action="{{ route('deleteUser.destroy', $qu->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-outline-danger btn-icon-text mr-2"
                                                                type="submit">
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
                                    {{ $users->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tab End User -->
                @endif

                <!-- Tab E Invoice -->
                <div class="tab-pane fade" id="invoice-1" role="tabpanel" aria-labelledby="invoice-tab">
                    <div class="col-sm-12  grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-xl-flex justify-content-between mb-2">
                                    <!-- Button trigger modal -->
                                    <!-- data-toggle="modal" data-target="#addUserModal"-->
                                    <a href="/e-invoice" class="btn btn-primary mb-5">E Invoice</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tab End Invoice -->

            </div>
        </div>
        <!-- Modal Kas -->
        <div class="modal fade" id="kasModal" tabindex="-1" aria-labelledby="kasModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="kasModalLabel">Tambah Buku Kas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{-- Modal Tambah / Add Kas --}}
                    <div class="modal-body">
                        <form action="/addKas" method="post">
                            @csrf
                            <input type="hidden" id="kasId" name="kas_id">
                            <div class="form-group">
                                <label for="nama_akun">Nama Akun</label>
                                <input type="text" class="form-control" id="nama_akun" name="nama_akun" required>
                            </div>
                            <div class="form-group">
                                <label for="tampil">Tampilkan</label>
                                <select name="tampil" id="tampil" class="form-select">
                                    <option value="y">Ya</option>
                                    <option value="t">Tidak</option>
                                </select>
                            </div>
                            <!-- Tambahkan field form lainnya sesuai kebutuhan -->
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Kas-->

        <!-- Modal Pelanggan -->
        <div class="modal fade" id="addPelangganModal" tabindex="-1" aria-labelledby="addPelangganModalLabel"
            aria-hidden="true">
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
                        <form id="addPelangganForm" action="{{ route('addPelanggan.store') }}" method="post">
                            @csrf
                            <input type="hidden" id="pelangganId" name="pelangganId">
                            <div class="form-group">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan"
                                    required>
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
            // Ajax Store and Edit Akun Kas
            $(document).ready(function() {
                $('#kasModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('item-id'); // Extract info from data-* attributes
                    var namaAkun = button.data('item-nama-akun');
                    var tampil = button.data('item-tampil');

                    var modal = $(this);
                    var formAction = "{{ route('addKas.store') }}";

                    if (id) {
                        modal.find('.modal-title').text('Edit Buku Kas');
                        formAction = "{{ route('addKas.update', ':id') }}".replace(':id', id);
                        modal.find('#kasId').val(id);
                        modal.find('#nama_akun').val(namaAkun);
                        modal.find('#tampil').val(tampil);
                    } else {
                        modal.find('.modal-title').text('Tambah Buku Kas');
                        modal.find('#kasId').val('');
                        modal.find('#nama_akun').val('');
                        modal.find('#tampil').val('y');
                    }

                    modal.find('#kasForm').attr('action', formAction);
                });
            });
            // End Store and edit

            // Ajax Store dan Edit Pelanggan
            $(document).ready(function() {
                $('#addPelangganModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('item-id'); // Extract info from data-* attributes
                    var namaPelanggan = button.data('item-nama-pelanggan');
                    var alamat = button.data('item-alamat');

                    var modal = $(this);
                    var formAction = "{{ route('addPelanggan.store') }}";

                    if (id) {
                        modal.find('.modal-title').text('Edit Pelanggan');
                        formAction = "{{ route('editPelanggan.update', ':id') }}".replace(':id', id);
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

            // Ajax Store dan Edit User
            $(document).ready(function() {
                $('#addUserModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('item-id'); // Extract info from data-* attributes
                    var nama = button.data('item-nama');
                    var username = button.data('item-username');
                    var email = button.data('item-email');
                    var password = button.data('item-password');
                    var role = button.data('item-role');

                    var modal = $(this);
                    var formAction = "{{ route('addUser.store') }}";

                    if (id) {
                        modal.find('.modal-title').text('Edit User');
                        formAction = "{{ route('editUser.update', ':id') }}".replace(':id', id);
                        modal.find('#userId').val(id);
                        modal.find('#name').val(nama);
                        modal.find('#username').val(username);
                        modal.find('#email').val(email);
                        modal.find('#role').val(role);
                        modal.find('#password').val('******');
                    } else {
                        modal.find('.modal-title').text('Tambah User');
                        modal.find('#userId').val('');
                        modal.find('#name').val('');
                        modal.find('#username').val('');
                        modal.find('#email').val('');
                        modal.find('#password').val('');
                    }

                    modal.find('#addUserForm').attr('action', formAction);
                });
            });
            // End Store dan edit
            $(document).ready(function() {
                $('#togglePassword').on('click', function() {
                    var passwordField = $('#password');
                    var passwordFieldType = passwordField.attr('type');
                    var icon = $(this).find('i');

                    if (passwordFieldType === 'password') {
                        passwordField.attr('type', 'text');
                        icon.removeClass('fa-eye').addClass('fa-eye-slash');
                    } else {
                        passwordField.attr('type', 'password');
                        icon.removeClass('fa-eye-slash').addClass('fa-eye');
                    }
                });
            });
        </script>
    @endsection
