@extends('layouts._header')
@section('title','Data User')

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
<div class="container mt-4">
<!-- Success Message -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
<!-- Success Message -->
@if(session('message'))
<div class="alert alert-info alert-dismissible fade show" role="alert">
    {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">
          <a href="#" class="btn btn-secondary" data-toggle="modal" data-target="#addUserModal">Tambah User</a>

        </h4>
        <div class="table-container">

          <table class="table table-dark grid-margin">
            <thead>
              <tr>
                <th colspan="8" style="text-align: center;"> Table User </th>
            </tr>
            <tr>
              <th> No </th>
              <th> Nama </th>
              <th> Role </th>
              <th> Status </th>
              <th> Username </th>
              {{-- <th> Email </th> --}}
              <th> Tanggal Login</th>
              <th style="text-align: center;"> Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($users as $item)
            <tr>
              <td> {{$loop->iteration}} </td>
              <td> {{$item->name}} </td>
              <td> {{$item->role}} </td>
              <td>
                <a href="statusUser/{{$item->id}}" class="btn btn-sm btn-{{$item->status_aktif ? 'success' : 'danger'}}"> 
                  {{$item->status_aktif ? 'Enable' : 'Disable'}}
                </a>
              </td>
              <td> {{$item->username}} </td>
              {{-- <td> {{$item->email}} </td> --}}
              <td> {{$item->tanggal_login}} </td>
              <td>
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#detailModal" data-id="{{ $item->id }}">
                    Detail
                </button>   
                <a href="{{ route('user.edit', ['idUser'=> $item->id]) }}" class="btn btn-md btn-secondary"> Edit</a>
                <button type="button" class="btn btn-danger btn-md" data-toggle="modal" data-target="#deleteModal" data-id="{{ $item->id }}">
                    Hapus
                  </button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
         <!-- Modal Users -->
         <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                  <div class="modal-body">
                      <!-- Form content goes here -->
                      <form id="addUserForm" action="{{route('addUser.store')}}" method="post">
                          @csrf
                          {{-- @method('PUT') --}}
                          <input type="hidden" id="userId" name="userId">
                          <div class="form-group">
                              <label for="name">Nama</label>
                              <input type="text" class="form-control" id="name" name="name" required autocomplete="off">
                          </div>
                          <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required autocomplete="off">
                          </div>
                          <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                              <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
                              <div class="input-group-append">
                                  <span class="input-group-text" id="togglePassword">
                                      <i class="fas fa-eye"></i>
                                  </span>
                              </div>
                          </div>
                        </div>
                          <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com" autocomplete="off" required autocomplete="off">
                          </div>
                          <div class="form-group">
                            <label for="Role">Role</label>
                            <select name="role" id="role" class="form-control">
                              <option value="Karyawan">Admin</option>
                              <option value="Admin">Manager</option>
                              <option value="Owner">Owner</option>
                            </select>
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
         <!-- End Modal Users -->
  <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailModalLabel">Detail User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <p><strong>ID:</strong> <span id="spanId"></span></p>
            <p><strong>Nama User:</strong> <span id="spanName"></span></p>
            <p><strong>Role/Jabatan:</strong> <span id="spanRole"></span></p>
            <p><strong>Username:</strong> <span id="spanUsername"></span></p>
            <p><strong>Status:</strong> <span id="spanStatus"></span></p>
            <p><strong>Email:</strong> <span id="spanEmail"></span></p>
            <p><strong>Tanggal Login:</strong> <span id="spanTanggalLogin"></span></p>
            <p><strong>di Buat tanggal:</strong> <span id="spanCreated"></span></p>
            <p><strong>di Pebarui tanggal:</strong> <span id="spanUpdated"></span></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          {{-- <a type="button" class="btn btn-danger" href="/exportDetailDebit">Export</a> --}}
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
              User tidak dapat di kembalikan, apakah anda yakin menghapusnya? <br> ID: <span id="nomorToDelete"></span>
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
        var idUser = button.data('id');
        getTransactionDetails(idUser);
    });
        // Ajax request to get transaction details
        function getTransactionDetails(idUser) {
            $.ajax({
                url: '/userDetail/' + idUser,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#spanId').text(data.id);
                    $('#spanName').text(data.name);
                    $('#spanRole').text(data.role);
                    $('#spanStatus').text(data.status_aktif);
                    $('#spanUsername').text(data.username);
                    $('#spanEmail').text(data.email);
                    $('#spanCreated').text(data.created_at);
                    $('#spanUpdated').text(data.updated_at);
                    $('#spanTanggalLogin').text(data.tanggal_login);
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(error);
                }
            });
        }

        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Tombol yang memicu modal
            var id = button.data('id'); // Dapatkan data-id-bukti dari tombol
            var modal = $(this);
            var action = "{{ route('deleteUser.destroy', ':id') }}";
            action = action.replace(':id', id);
            modal.find('#deleteForm').attr('action', action);
            
            // Tampilkan id di dalam modal
            modal.find('#nomorToDelete').text(id);
        });
    });
    $(document).ready(function() {
        $('#addUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('item-id'); // Extract info from data-* attributes
            var nama = button.data('item-nama');
            var username = button.data('item-username');
            var email = button.data('item-email');
            var password = button.data('item-password');
            var role = button.data('item-role');

            var modal = $(this);
            var formAction = "{{route('addUser.store')}}";

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
  </script>
@endsection