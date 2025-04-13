@extends('layouts._header')
@section('title','Data Kategori')

@section('content')
<style>
  .col {
    color:#e6ebfc
  }
</style>
  <div id="messageContainer" class="container mt-2 my-2">
  </div>
  <div class="container mt-2 my-2">
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
<div class="form-group">
  <label for="categorySelector">Pilih Kategori</label>
  <select id="categorySelector" class="form-control">
    <option value="kas">Kategori Kas</option>
    <option value="supplier">Kategori Supplier</option>
  </select>
</div>

<div id="kategoriKas" class="categoryForm">
  <h4 class="card-title">Kategori Kas</h4>
  <div class="col-sm-6 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <table class="table table-bordered my-2">
          <thead>
            <h4 class="card-title">Pemasukan</h4>
            <hr>
            <tr>
              <th style="text-align: center; width:20px;"> No </th>
              <th style="text-align: center; width:170px;"> Nama Kategori </th>
              <th style="text-align: center;"> Aksi </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pemasukan as $q)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td class="editable" data-item-id="{{$q->id}}">{{$q->name}}</td>
                <td class="action-buttons" data-item-id="{{$q->id}}">
                  <div class="row">
                      <a href="#" class="btn btn-outline-info btn-icon-text mr-2 mb-2 editButton" data-item-id="{{$q->id}}">
                        Edit
                      </a>
                      <button class="btn btn-outline-danger btn-icon-text mr-2 deleteButtonPemasukan" type="submit" data-item-id="{{$q->id}}" data-item-name="{{$q->name}}">
                        Delete
                    </button>
                  </div>
              </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="col">
          <div id="cardContainerPemasukan"></div>
        </div>
        <form action="/addSubcategories" method="POST">
          @csrf
          <div class="form-group">
            <div class="row">
              <div class="col">
                <input type="hidden" class="form-control" value="1" name="kategori_id" style="width:230px; height:40px;">
                <input type="text" class="form-control" name="name" style="width:230px; height:40px;" autocomplete="off">
              </div>
              <div class="col">
                <button type="submit" class="btn btn-inverse-primary btn-fw">Tambah Pemasukan</button>
              </div>
            </div>
          </div>
        </form>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <table class="table table-bordered my-2">
        <thead>
            <h4 class="card-title">Pengeluaran</h4>
            <hr>
            <tr>
              <th style="text-align: center; width:10px;"> No </th>
              <th style="text-align: center; width:170px;"> Nama Kategori </th>
              <th style="text-align: center;"> Aksi </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengeluaran as $q)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td class="editable" data-item-id="{{$q->id}}">{{$q->name}}</td>
                <td class="action-buttons" data-item-id="{{$q->id}}">
                  <div class="row">
                      <a href="#" class="btn btn-outline-info btn-icon-text mr-2 mb-2 editButton" data-item-id="{{$q->id}}">
                          Edit
                      </a>
                          <button class="btn btn-outline-danger btn-icon-text mr-2 deleteButtonPengeluaran" type="submit" data-item-id="{{$q->id}}" data-item-name="{{$q->name}}">
                            Delete
                        </button>
                  </div>
              </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="col">
      <div id="cardContainerPengeluaran"></div>
    </div>
      <form action="/addSubcategories" method="POST">
        @csrf
        <div class="form-group">
          <div class="row">
            <div class="col">
              <input type="hidden" class="form-control" value="2" name="kategori_id" style="width:230px; height:40px;">
              <input type="text" class="form-control" name="name" style="width:230px; height:40px;" autocomplete="off">
            </div>
            <div class="col">
              <button type="submit" class="btn btn-inverse-primary btn-fw">Tambah Pengeluaran</button>
            </div>
          </div>

        </div>
      </form>
      {{-- <button href="#" class="btn btn-inverse-primary btn-fw my-4" data-toggle="modal" data-target="#addKategoriModal">Tambah Pengeluaran</button> --}}
  </div>
  </div>
  </div>

</div>

<div id="kategoriSupplier" class="categoryForm" style="display: none;">
  <h4 class="card-title">Kategori Supplier</h4>
  <div class="col-sm-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">

        <table class="table table-bordered my-2">
          <thead>
            <h4 class="card-title">Supplier</h4>
            <hr>
            <tr>
              <th style="text-align: center; width:20px;"> No </th>
              <th style="text-align: center; width:300px;"> Nama Kategori </th>
              <th style="text-align: center; width:170px;"> Aksi </th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pemasukanSupplier as $q)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td class="editSupplier" data-item-id="{{$q->id}}">{{$q->name}}</td>
                <td class="buttonsSupplier" data-item-id="{{$q->id}}">
                  <div class="row">
                      <a href="#" class="btn btn-outline-info btn-icon-text mr-2 mb-2 editBtnSupplier" data-item-id="{{$q->id}}">
                        Edit
                      </a>
                      <button class="btn btn-outline-danger btn-icon-text mr-2 deleteBtnSupplierPemasukan" type="submit" data-item-id="{{$q->id}}" data-item-name="{{$q->name}}">
                        Delete
                    </button>
                  </div>
              </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        <div class="col">
          <div id="cardContainerPemasukanSupplier"></div>
        </div>
        <form action="/addCategoriSupplier" method="POST">
          @csrf
          <div class="form-group">
            <div class="row">
              <div class="col">
                <input type="hidden" class="form-control" value="Supplier" name="kategori" style="width:230px; height:40px;">
                <input type="text" class="form-control" name="name" style="width:400px; height:40px;" autocomplete="off">
              </div>
              <div class="col">
                <button type="submit" class="btn btn-inverse-primary btn-fw">Tambah Kategori Supplier</button>
              </div>
            </div>
          </div>
        </form>
    </div>
  </div>
    {{-- <div class="card">
      <div class="card-body">
        <table class="table table-bordered my-2">
          <thead>
              <h4 class="card-title">Supplier Penjualan</h4>
              <hr>
              <tr>
                <th style="text-align: center; width:10px;"> No </th>
                <th style="text-align: center; width:170px;"> Nama Kategori </th>
                <th style="text-align: center;"> Aksi </th>
              </tr>
          </thead>
          <tbody>
              @foreach ($pengeluaranSupplier as $q)
              <tr>
                  <td>{{$loop->iteration}}</td>
                  <td class="editSupplier" data-item-id="{{$q->id}}">{{$q->name}}</td>
                  <td class="buttonsSupplier" data-item-id="{{$q->id}}">
                    <div class="row">
                        <a href="#" class="btn btn-outline-info btn-icon-text mr-2 mb-2 editBtnSupplier" data-item-id="{{$q->id}}">
                            Edit
                        </a>
                            <button class="btn btn-outline-danger btn-icon-text mr-2 deleteBtnSupplierPengeluaran" type="submit" data-item-id="{{$q->id}}" data-item-name="{{$q->name}}">
                              Delete
                          </button>
                    </div>
                </td>
              </tr>
              @endforeach
          </tbody>
      </table>
      <div class="col">
        <div id="cardContainerPengeluaranSupplier"></div>
      </div>
        <form action="/addSubcategories" method="POST">
          @csrf
          <div class="form-group">
            <div class="row">
              <div class="col">
                <input type="hidden" class="form-control" value="Piutang" name="kategori" style="width:230px; height:40px;">
                <input type="text" class="form-control" name="name" style="width:230px; height:40px;" autocomplete="off">
              </div>
              <div class="col">
                <button type="submit" class="btn btn-inverse-primary btn-fw">Tambah Pengeluaran</button>
              </div>
            </div>

          </div>
        </form>
        {{-- <button href="#" class="btn btn-inverse-primary btn-fw my-4" data-toggle="modal" data-target="#addKategoriModal">Tambah Pengeluaran</button> -}}
    </div>
    </div> --}}
  </div>

</div>


  <div class="modal fade" id="addKategoriModal" tabindex="-1" aria-labelledby="addKategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addKategoriModalLabel">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form content goes here -->
                <form id="addPelangganForm" action="{{route('addPelanggan.store')}}" method="post">
                    @csrf
                    <div class="form-group">
                      <label for="">Nama Kategori</label><br>
                      <input type="text" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Akun</label><br>
                        <select name="nama_akun" id="nama_akun" style="width: 250px;">
                          @foreach ($kas as $qk)
                              <option value="{{$qk->nama_akun}}">{{$qk->nama_akun}}</option>
                          @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                      <label for="alamat">Kategori</label><br>
                      <select name="name" id="name" style="width: 250px;">
                      @foreach ($kategori as $qk)
                      <option value="{{$qk->name}}">{{$qk->name}}</option>
                      @endforeach
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
  <script src="../assets/js/jquery.min.js"></script>
<script>
$(document).ready(function() {
  $(document).on('click', '.editButton', function(e) {
      e.preventDefault();
      var itemId = $(this).data('item-id');
      var editableTd = $('td.editable[data-item-id="' + itemId + '"]');
      var currentText = editableTd.text();
      var actionButtonsTd = $('.action-buttons[data-item-id="' + itemId + '"]');
      
      actionButtonsTd.hide();

      var inputField = $('<input type="text" class="form-control" value="' + currentText + '">');
      
      var cancelButton = $('<button class="btn btn-secondary cancelEdit">Batal</button>');
      var saveButton = $('<button class="btn btn-primary saveEdit">Simpan</button>');

      cancelButton.on('click', function() {
          editableTd.text(currentText);
          actionButtonsTd.show();
      });

      saveButton.on('click', function() {
      var newText = inputField.val();
      $.ajax({
          url: '/editSubcategories/' + itemId + '?name=' + encodeURIComponent(newText), // Tambahkan parameter name ke URL
          method: 'PUT',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
              editableTd.text(newText);
              actionButtonsTd.show();
              location.reload();
          },
          error: function(xhr, status, error) {
              console.error('Error:', error);
          }
      });
  });

      // Append input field, cancel button, and save button to the editable td
      editableTd.empty().append(inputField).append(cancelButton).append(saveButton);

      // Focus on the input field
      inputField.focus();
  });

  $(document).on('click', '.editBtnSupplier', function(e) {
      e.preventDefault();
      var itemId = $(this).data('item-id');
      var editableTd = $('td.editSupplier[data-item-id="' + itemId + '"]');
      var currentText = editableTd.text();
      var actionButtonsTd = $('.buttonsSupplier[data-item-id="' + itemId + '"]');
      
      actionButtonsTd.hide();

      var inputField = $('<input type="text" class="form-control" value="' + currentText + '">');
      
      var cancelButton = $('<button class="btn btn-secondary cancelEdit">Batal</button>');
      var saveButton = $('<button class="btn btn-primary saveEdit">Simpan</button>');

      cancelButton.on('click', function() {
          editableTd.text(currentText);
          actionButtonsTd.show();
      });

      saveButton.on('click', function() {
      var newText = inputField.val();
      $.ajax({
          url: '/editCategoriSupplier/' + itemId + '?name=' + encodeURIComponent(newText), // Tambahkan parameter name ke URL
          method: 'PUT',
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
              editableTd.text(newText);
              actionButtonsTd.show();
              location.reload();
          },
          error: function(xhr, status, error) {
              console.error('Error:', error);
          }
      });
  });

      // Append input field, cancel button, and save button to the editable td
      editableTd.empty().append(inputField).append(cancelButton).append(saveButton);

      // Focus on the input field
      inputField.focus();
  });
});
// Delete / Perbarui Pemasukan
$(document).ready(function() {
    // Tangkap klik tombol "Delete Pemasukan"
    $(document).on('click', '.deleteButtonPemasukan', function(event) {
        event.preventDefault();

        var deleteButtonPemasukan = $(this);
        var itemId = deleteButtonPemasukan.data('item-id');
        var itemName = deleteButtonPemasukan.data('item-name');

        // Hapus semua kartu yang ada sebelum menambahkan kartu baru
        $('#cardContainerPemasukan').empty();

        // Tampilkan informasi dan tombol konfirmasi
        var cardHtml = `
            <div class="card my-2" style="background-color:#a00000; width:400px;">
                <div class="card-body">
                    <h5 class="card-title" style="color:#e6ebfc;">Anda mungkin memiliki pemasukan atau pengeluaran di kategori ${itemName}. Apa yang ingin Anda lakukan?</h5>
                    <select name="nama_akun" class="form-control my-4">
                      <option value="${itemId}" name="id"> Hapus semuanya </option>`;
        @foreach ($pemasukan as $q)
            if ('{{$q->id}}' != itemId) {
                cardHtml += `<option value="{{$q->id}}"> Pindahkan ke {{$q->name}}</option>`;
            }
        @endforeach

        cardHtml += `</select>
                    <button class="btn btn-success confirmDelete" data-item-id="${itemId}" data-item-name="${itemName}">Simpan</button>
                    <button class="btn btn-secondary cancelDelete">Batal</button>
                </div>
            </div>
        `;

        // Sisipkan kartu di bawah tabel
        $('#cardContainerPemasukan').append(cardHtml);
    });

    // Tangkap klik tombol "Delete Pengeluaran"
    $(document).on('click', '.deleteButtonPengeluaran', function(event) {
        event.preventDefault();

        var deleteButtonPengeluaran = $(this);
        var itemId = deleteButtonPengeluaran.data('item-id');
        var itemName = deleteButtonPengeluaran.data('item-name');

        // Hapus semua kartu yang ada sebelum menambahkan kartu baru
        $('#cardContainerPengeluaran').empty();

        // Tampilkan informasi dan tombol konfirmasi
        var cardHtml = `
            <div class="card my-2" style="background-color:#a00000; width:400px;">
                <div class="card-body">
                    <h5 class="card-title" style="color:#e6ebfc;">Anda mungkin memiliki pemasukan atau pengeluaran di kategori ${itemName}. Apa yang ingin Anda lakukan?</h5>
                    <select name="nama_akun" class="form-control my-4">
                        <option value="${itemId}" name="id"> Hapus semuanya </option>`;
        @foreach ($pengeluaran as $q)
            if ('{{$q->id}}' != itemId) {
                cardHtml += `<option value="{{$q->id}}"> Pindahkan ke {{$q->name}}</option>`;
            }
        @endforeach

        cardHtml += `</select>
                    <button class="btn btn-success confirmDelete" data-item-id="${itemId}" data-item-name="${itemName}">Simpan</button>
                    <button class="btn btn-secondary cancelDelete">Batal</button>
                </div>
            </div>
        `;

        // Sisipkan kartu di bawah tabel
        $('#cardContainerPengeluaran').append(cardHtml);
    });

    // Tangkap klik tombol "Ya" untuk konfirmasi penghapusan
    $(document).on('click', '.confirmDelete', function(event) {
        var confirmButton = $(this);
        var itemId = confirmButton.data('item-id');
        var itemName = confirmButton.data('item-name');
        var selectedCategoryId = $('[name="nama_akun"]').val(); // Ambil nilai yang dipilih dari opsi select
        var isDeleteAll = $('[name="id"]').is(':checked');
        if (isDeleteAll) {
            $.ajax({
                url: '/deleteSubcategories/' + itemId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    var successHtml = `
                        <div class="alert alert-success" role="alert">
                            Item dengan ID ${itemId} (${itemName}) berhasil dihapus.
                        </div>
                    `;
                    $('#messageContainer').html(successHtml);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        } else {
            $.ajax({
                url: '/moveSubcategories/' + itemId,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { name: $('[name="nama_akun"] option:selected').text() }, // Ambil teks dari opsi yang dipilih
                success: function(response) {
                    var successHtml = `
                        <div class="alert alert-success" role="alert">
                            Item dengan ID ${itemId} (${itemName}) berhasil dipindahkan ke kategori ${$('[name="nama_akun"] option:selected').text()}.
                        </div>
                    `;
                    $('#messageContainer').html(successHtml);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        // Hapus kartu konfirmasi dari DOM setelah pemrosesan
        $(this).closest('.card').remove();
    });

    // Tangkap klik tombol "Batal"
    $(document).on('click', '.cancelDelete', function(event) {
        $(this).closest('.card').remove();
    });
});

$(document).ready(function() {
    // Tangkap klik tombol "Delete Pemasukan"
    $(document).on('click', '.deleteBtnSupplierPemasukan', function(event) {
        event.preventDefault();

        var deleteButtonPemasukan = $(this);
        var itemId = deleteButtonPemasukan.data('item-id');
        var itemName = deleteButtonPemasukan.data('item-name');

        // Hapus semua kartu yang ada sebelum menambahkan kartu baru
        $('#cardContainerPemasukanSupplier').empty();

        // Tampilkan informasi dan tombol konfirmasi
        var cardHtml = `
            <div class="card my-2" style="background-color:#a00000; width:400px;">
                <div class="card-body">
                    <h5 class="card-title" style="color:#e6ebfc;">Anda mungkin memiliki Piutang atau Utang di kategori ${itemName}. Apa yang ingin Anda lakukan?</h5>
                    <select name="nama_akun" class="form-control my-4">
                      <option value="${itemId}" name="id"> Hapus semuanya </option>`;
        @foreach ($pemasukanSupplier as $q)
            if ('{{$q->id}}' != itemId) {
                cardHtml += `<option value="{{$q->id}}"> Pindahkan ke {{$q->name}}</option>`;
            }
        @endforeach

        cardHtml += `</select>
                    <button class="btn btn-success confirmDelete" data-item-id="${itemId}" data-item-name="${itemName}">Simpan</button>
                    <button class="btn btn-secondary cancelDelete">Batal</button>
                </div>
            </div>
        `;

        // Sisipkan kartu di bawah tabel
        $('#cardContainerPemasukanSupplier').append(cardHtml);
    });

    // Tangkap klik tombol "Delete Pengeluaran"
    $(document).on('click', '.deleteBtnSupplierPengeluaran', function(event) {
        event.preventDefault();

        var deleteButtonPengeluaran = $(this);
        var itemId = deleteButtonPengeluaran.data('item-id');
        var itemName = deleteButtonPengeluaran.data('item-name');

        // Hapus semua kartu yang ada sebelum menambahkan kartu baru
        $('#cardContainerPengeluaranSupplier').empty();

        // Tampilkan informasi dan tombol konfirmasi
        var cardHtml = `
            <div class="card my-2" style="background-color:#a00000; width:400px;">
                <div class="card-body">
                    <h5 class="card-title" style="color:#e6ebfc;">Anda mungkin memiliki Piutang atau Utang di kategori ${itemName}. Apa yang ingin Anda lakukan?</h5>
                    <select name="nama_akun" class="form-control my-4">
                        <option value="${itemId}" name="id"> Hapus semuanya </option>`;
        @foreach ($pengeluaranSupplier as $q)
            if ('{{$q->id}}' != itemId) {
                cardHtml += `<option value="{{$q->id}}"> Pindahkan ke {{$q->name}}</option>`;
            }
        @endforeach

        cardHtml += `</select>
                    <button class="btn btn-success confirmDelete" data-item-id="${itemId}" data-item-name="${itemName}">Simpan</button>
                    <button class="btn btn-secondary cancelDelete">Batal</button>
                </div>
            </div>
        `;

        // Sisipkan kartu di bawah tabel
        $('#cardContainerPengeluaranSupplier').append(cardHtml);
    });

    // Tangkap klik tombol "Ya" untuk konfirmasi penghapusan
    $(document).on('click', '.confirmDelete', function(event) {
        var confirmButton = $(this);
        var itemId = confirmButton.data('item-id');
        var itemName = confirmButton.data('item-name');
        var selectedCategoryId = $('[name="nama_akun"]').val(); // Ambil nilai yang dipilih dari opsi select
        var isDeleteAll = $('[name="id"]').is(':checked');
        if (isDeleteAll) {
            $.ajax({
                url: '/deleteCategoriSupplier/' + itemId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    var successHtml = `
                        <div class="alert alert-success" role="alert">
                            Item dengan ID ${itemId} (${itemName}) berhasil dihapus.
                        </div>
                    `;
                    $('#messageContainer').html(successHtml);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        } else {
            $.ajax({
                url: '/moveCategoriSupplier/' + itemId,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { name: $('[name="nama_akun"] option:selected').text() }, // Ambil teks dari opsi yang dipilih
                success: function(response) {
                    var successHtml = `
                        <div class="alert alert-success" role="alert">
                            Item dengan ID ${itemId} (${itemName}) berhasil dipindahkan ke kategori ${$('[name="nama_akun"] option:selected').text()}.
                        </div>
                    `;
                    $('#messageContainer').html(successHtml);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        // Hapus kartu konfirmasi dari DOM setelah pemrosesan
        $(this).closest('.card').remove();
    });

    // Tangkap klik tombol "Batal"
    $(document).on('click', '.cancelDelete', function(event) {
        $(this).closest('.card').remove();
    });
});

$(document).ready(function() {
    $('#categorySelector').on('change', function() {
      var selectedValue = $(this).val();
      if (selectedValue === 'kas') {
        $('#kategoriKas').show();
        $('#kategoriSupplier').hide();
      } else if (selectedValue === 'supplier') {
        $('#kategoriKas').hide();
        $('#kategoriSupplier').show();
      }
    });
  });
</script>
  
@endsection