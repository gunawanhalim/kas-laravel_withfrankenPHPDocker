@extends('layouts._header')
@section('title', 'Kas Bank ' . $id_kas->nama_akun)

@section('content')
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> --}}
    <style>
        .custom-modal {
            display: none;
            position: absolute;
            z-index: 1;
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 450px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .5);
        }

        .custom-modal .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .custom-modal .close:hover,
        .custom-modal .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .bg-income {
            background-color: #d4edda;
            /* Hijau muda */
        }

        .bg-expense {
            background-color: #f8d7da;
            /* Merah muda */
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            margin: 0;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            /* Pastikan z-index lebih tinggi dari elemen lain */
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .header-cell {
            text-align: center;
            vertical-align: middle;
        }

        .header-cell .top-header {
            border-bottom: 1px solid #ddd;
            /* Optional: border to separate headers */
            padding-bottom: 5px;
            font-weight: bold;
        }

        .header-cell .bottom-header {
            padding-top: 5px;
        }

        .table-container {
            transform-origin: 0 0;
            /* Menetapkan titik awal transformasi */
        }

        @media (min-width: 425px) {
            .table-container {
                transform: scale(0.6);
                /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
            }
        }

        @media (min-width: 768px) {
            .table-container {
                transform: scale(0.7);
                /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
            }
        }

        @media (min-width: 1024px) {
            .table-container {
                transform: scale(0.7);
                /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
            }
        }

        @media (min-width: 1340px) {
            .table-container {
                transform: scale(0.98);
                /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
            }
        }

        @media (min-width: 1640px) {
            .table-container {
                transform: scale(0.6);
                /* Mengurangi skala tabel menjadi 60% dari ukuran aslinya */
            }
        }
    </style>
    @php
        $today = date('Y-m-d H:i:s');
    @endphp
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/beranda.php">Beranda</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $id_kas->nama_akun }}</li>
            </ol>
        </nav>
        @php
            $formattedSaldo =
                $saldo < 0
                    ? 'Rp -' . number_format(abs($saldo), 0, ',', '.')
                    : 'Rp ' . number_format($saldo, 0, ',', '.');
        @endphp


        <h3>Saldo: {{ $formattedSaldo }}</h3>
    </div>
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

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
    <div class="page-header">
        <div class="template-demo">
            <button type="button" class="btn btn-success btn-icon-text" id="catatPemasukanBtn">
                <i class="mdi mdi-arrow-top-right"></i> Catat Pemasukan
            </button>
            <button type="button" id="catatPengeluaranBtn" class="btn btn-danger btn-icon-text">
                <i class="mdi mdi-arrow-bottom-left"></i> Catat Pengeluaran
            </button>
            <!-- Tombol Transfer -->
            <button type="button" id="transferKas" class="btn btn-md btn-secondary btn-icon-text" data-bs-toggle="modal"
                data-bs-target="#transferModal">
                <i class="mdi mdi-replay"></i> Transfer
            </button>
            <!-- Modal -->
            <div id="catatPemasukanModal" class="custom-modal">
                <span class="close">&times;</span>
                <form id="transactionForm" action="{{ route('kas_bank.storeIncome') }}" method="POST">
                    @csrf
                    <input type="text" name="nama_user" class="form-control" hidden value="{{ Auth::user()->name }}">
                    <input type="datetime-local" name="tanggal_log" class="form-control" hidden
                        value="{{ Auth::user()->tanggal_login }}">
                    <input type="text" name="nama_akun" class="form-control" hidden value="{{ $id_kas->nama_akun }}">
                    <div class="form-group">
                        <label for="type">Tipe</label>
                        <select id="type" name="kategori" class="form-control" required>
                            <option value="1">Catat Pemasukan</option>
                            <option value="2">Catat Pengeluaran</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Tanggal</label>
                        <input type="datetime-local" name="tanggal_bukti" class="form-control" autocomplete="off"
                            value="{{ $today }}" required>
                    </div>
                    <div class="form-group">
                        <label for="jumlah">Nominal</label>
                        <input type="text" name="jumlah" class="form-control" oninput="formatAngka(this)"
                            autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label for="subcategories_id">Kategori</label>
                        <select id="subcategories_id" name="subcategories_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            {{-- @foreach ($subcategories as $item)
                        <option value="{{$item->nama_akun}}">{{$item->nama_akun}}</option>
                        @endforeach --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Deskripsi</label>
                        <textarea name="keterangan" id="keterangan" cols="40" rows="5" autocomplete="off"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    {{-- <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Transfer Kas/Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kas_bank.transfer') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="from_account">Dari Akun</label><br>
                        <input type="text" name="from_account" class="form-control" value="{{$id_kas->nama_akun}}" readonly>
                        {{-- <select id="from_account" name="from_account" class="form-control">
                            @foreach ($semuaKas as $item)
                            <option value="{{$item->id}}">{{$item->nama_akun}}- Tanggal Bukti: {{$item->tanggal_bukti}}-Saldo:Rp. {{ number_format($item->jumlah, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="to_account">Ke Akun</label>
                        <select name="to_account" class="form-control" required>
                            @foreach ($kas as $item)
                                @if ($item->nama_akun != $id_kas->nama_akun)
                                    <option value="{{ $item->nama_akun }}">{{ $item->nama_akun }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Jumlah</label>
                        <input type="number" name="amount" id="jumlah" class="form-control" required min="1">
                    </div>
                    <div class="form-group">
                        <label for="description">Keterangan</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Transfer</button>
                </form>
            </div>
        </div>
    </div>
</div> --}}
    <!-- Modal -->
    <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transferModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="transferForm" action="{{ route('kas_bank.transfer') }}" method="POST">
                        @csrf
                        <input type="hidden" name="transfer_id" id="transfer_id">
                        <div class="form-group">
                            <label for="from_account">Dari Akun</label><br>
                            <input type="text" name="from_account" id="from_account" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="to_account">Ke Akun</label>
                            <select name="to_account" id="to_account" class="form-control" required>
                                @foreach ($kas as $item)
                                    @if ($item->nama_akun != $id_kas->nama_akun)
                                        <option value="{{ $item->nama_akun }}">{{ $item->nama_akun }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Jumlah</label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Keterangan</label>
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary" id="submitButton"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">KAS {{ $id_kas->nama_akun }}</h4>
                    <div class="my-2">
                        <form method="GET" action="/akun_kas/{{ $id_kas->nama_akun }}" id="searchForm">
                            {{-- {{dd($kas_bank)}} --}}
                            <label>Filter berdasarkan :</label>
                            <select class="form-control form-control-sm mb-3" style="width: 200px; height:30px;"
                                aria-label=".form-select-sm example name="category">
                                <option value="">All</option>
                                <option value="kategori" @if (request('kas_bank') == 'kategori') selected @endif>Kategori
                                </option>
                                <option value="jumlah" @if (request('kas_bank') == 'jumlah') selected @endif>Total</option>
                                <option value="keterangan" @if (request('kas_bank') == 'keterangan') selected @endif>Keterangan
                                </option>
                                <option value="nama_user" @if (request('kas_bank') == 'nama_user') selected @endif>Nama User
                                </option>
                            </select>
                            <div class="dropdown dropdown-arrow-none">
                                <button class="btn p-0 text-dark dropdown-toggle" target="_blank" name="kas_pdf">
                                    <i class="mdi mdi-file-pdf mr-2"></i>PDF </button>
                                <button class="btn p-0 text-dark dropdown-toggle" name="kas_excel" target="_blank">
                                    <i class="mdi mdi-file-excel mr-2"></i>EXCEL </button>
                            </div>
                            <div class="mb-2 my-2">
                                <input type="text" name="search" placeholder="Search Data..."
                                    value="{{ request('search') }}" class="form" autocomplete="off">
                                <input type="date" name="start_date" value="{{ request('start_date') }}">
                                <input type="date" name="end_date" value="{{ request('end_date') }}">
                                <button type="submit" class="btn btn-md btn-outline-success"
                                    style="width: 100px; height:30px;">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-container">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> No </th>
                                    <th> Tipe </th>
                                    <th> Tanggal </th>
                                    <th> Kategori </th>
                                    <th style="width: 100%;" class="header-cell">
                                        <div class="top-header">Link Kategori</div>
                                        <div class="bottom-header">Keterangan</div>
                                    </th>
                                    <th> Nominal </th>
                                    <th> Saldo </th>
                                    {{-- <th> Nama User </th> --}}
                                    <th> Edit </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="8" style="text-align:center;">
                                        <strong>Saldo Awal : {{ number_format($saldoAwal, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @php
                                    $i = 0;
                                    $saldo = 0;
                                @endphp
                                @foreach ($kas_bank as $item)
                                    @php
                                        // Mendapatkan nilai saldo awal
                                        $jumlah = $item->jumlah;
                                        if ($i == 0) {
                                            $saldo = $jumlah;
                                        } else {
                                            $saldo += $item->jumlah;
                                        }
                                        $i = $i + 1;
                                    @endphp
                                    <tr
                                        class="{{ $item->categories->name == 'Pengeluaran' ? 'table-danger' : ($item->categories->name == 'Pemasukan' ? 'table-success' : '') }}">
                                        <td style="text-align: center; width:10px;"> {{ $loop->iteration }} </td>
                                        <td>
                                            @if ($item->categories->name == 'Pengeluaran')
                                                <i class="mdi mdi-arrow-bottom-left text-danger" title="Expense"></i>
                                            @elseif($item->categories->name == 'Pemasukan')
                                                <i class="mdi mdi-arrow-top-right text-success" title="Income"></i>
                                            @else
                                                {{ $item->categories->name }}
                                            @endif
                                        </td>
                                        <td style="width:10px;"> {{ $item->tanggal_bukti }} </td>
                                        <td style="width:10px;">{{ $item->kategori }}</td>
                                        <td
                                            style="
                                width: 100px;
                                max-width: 100%;
                                word-break: break-all;
                                word-wrap: break-word;
                                white-space: normal;
                                overflow-wrap: break-word;
                                padding: 1px;
                                vertical-align: top;
                            ">
                                            @if ($item->from == 'Piutang')
                                                <a href="/detail-penjualan-pelanggan/nama_pelanggan={{ $item->nama_pelanggan }}&nomor_bukti={{ $item->nomor_bukti }}"
                                                    style="color:#000000; font-size:12px;">
                                                    New Credit from {{ $item->nama_pelanggan }}
                                                </a>
                                            @elseif ($item->from == 'Utang')
                                                <a href="/detail-pembelian-pelanggan/nama_supplier={{ $item->nama_sales_utang }}&nomor_bukti={{ $item->nomor_bukti }}"
                                                    style="color:#000000; font-size:12px;">
                                                    New Debit from {{ $item->nama_sales_utang }}
                                                </a>
                                            @elseif ($item->from == 'Transfer' && $item->jumlah < 0)
                                                <a href="/akun_kas/{{ $item->toAccount->nama_akun }}"
                                                    style="color:#000000; font-size:12px;">
                                                    Transfer To {{ $item->toAccount->nama_akun }}
                                                </a>
                                            @elseif ($item->from == 'Transfer' && $item->jumlah > 0)
                                                <a href="/akun_kas/{{ $item->fromAccount->nama_akun }}"
                                                    style="color:#000000; font-size:12px;">
                                                    Transfer From {{ $item->fromAccount->nama_akun }}
                                                </a>
                                            @endif

                                            <!-- Keterangan -->
                                            <div style="font-size: 12px;"><strong>{{ $item->keterangan }}</strong></div>

                                            <!-- Display the user who recorded the entry once -->
                                            <div style="font-size: 12px;">Di catat oleh: {{ $item->nama_user }}</div>

                                        </td>
                                        <td style="width:10px;">Rp. {{ number_format(abs($item->jumlah), 0, ',', '.') }}
                                        </td>
                                        <td style="width:10px;">Rp. {{ number_format($saldo, 0, ',', '.') }}</td>
                                        <td style="width:10px;">
                                            @if ($item->from == 'Kas')
                                                <div class="dropdown">
                                                    <button class="dropbtn">
                                                        <img src="{{ asset('assets/images/list-edit.png') }}"
                                                            style="width: 20px; height: 20px;" alt="Actions">
                                                    </button>
                                                    <div class="dropdown-content">
                                                        <a href="{{ route('kas_bank.show', $item->id) }}">Show</a>
                                                        <a href="{{ route('kas_bank.edit', $item->id) }}">Edit</a>
                                                        <a href="#"
                                                            onclick="event.preventDefault(); deleteItem('{{ route('kas_bank.destroy', $item->id) }}')">Delete</a>
                                                    </div>
                                                </div>
                                            @elseif ($item->from == 'Transfer')
                                                <div class="dropdown">
                                                    <button class="dropbtn">
                                                        <img src="{{ asset('assets/images/list-edit.png') }}"
                                                            style="width: 20px; height: 20px;" alt="Actions">
                                                    </button>
                                                    <div class="dropdown-content">
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#transferModal" data-id="{{ $item->id }}"
                                                            class="edit-transfer-link">Edit Transfer</a>
                                                        {{-- <a href="{{ route('transfers.edit',['id' => $item->id] ) }}">Edit Transfer</a> --}}
                                                        <a href="#"
                                                            onclick="event.preventDefault(); deleteTransfer('{{ route('transfers.destroy', $item->id) }}')">Delete
                                                            Transfer</a>
                                                    </div>
                                                </div>
                                                {{-- <img src="{{ asset('assets/images/list-edit.png') }}" title="Kas ini terhubung dengan transfer dari kas yang lain, silahkan hapus" style="width: 20px; height: 20px;"> --}}
                                            @else
                                                <img src="{{ asset('assets/images/list-edit.png') }}"
                                                    title="Kas ini terhubung dengan hutang atau piutang silahkan mengeditnya di sana"
                                                    style="width: 20px; height: 20px;">
                                            @endif
                                        </td>
                                        {{-- <td>
                                    <a href="{{ route('kas_bank.show', $item->id) }}">Show</a>
                                    <a href="{{ route('kas_bank.edit', $item->id) }}">Edit</a>
                                    <form action="{{ route('kas_bank.destroy', $item->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">Delete</button>
                                    </form>
                                </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="my-5">
                    {{ $kas_bank->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('node_modules/select2/dist/js/select2.min.js') }}"></script>
    <script>
        function deleteItem(url) {
            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                var csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';
                form.appendChild(csrfField);

                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function deleteTransfer(url) {
            if (confirm('Apakah Anda yakin ingin menghapus Transfer ini?')) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                var csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = '{{ csrf_token() }}';
                form.appendChild(csrfField);

                var methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }
        $(document).ready(function() {
            var modal = $('#catatPemasukanModal');
            var catatPemasukanBtn = $('#catatPemasukanBtn');
            var catatPengeluaranBtn = $('#catatPengeluaranBtn');
            var span = $('.close');
            var typeSelect = $('#type');
            var namaAkun = "{{ $id_kas->nama_akun }}";
            var form = $('#transactionForm');
            var modalTransfer = $('#transferModal');
            var transferKasBtn = $('#transferKas');

            function showModal(type) {
                var offset = (type === '1') ? catatPemasukanBtn.offset() : catatPengeluaranBtn.offset();
                modal.css({
                    display: 'block',
                    top: offset.top + ((type === '2') ? catatPemasukanBtn.outerHeight() :
                        catatPengeluaranBtn.outerHeight()),
                    left: offset.left
                });

                if (type === '1') {
                    modal.removeClass('bg-expense').addClass('bg-income');
                    form.attr('action', '{{ route('kas_bank.storeIncome') }}');
                } else if (type === '2') {
                    modal.removeClass('bg-income').addClass('bg-expense');
                    form.attr('action', '{{ route('kas_bank.storeExpense') }}');
                }

                typeSelect.val(type).trigger('change');
            }

            catatPemasukanBtn.click(function() {
                showModal('1');
            });

            catatPengeluaranBtn.click(function() {
                showModal('2');
            });

            span.click(function() {
                modal.hide();
            });

            transferKasBtn.click(function() {
                modalTransfer.modal('show');
            });

            // Tutup modal saat tombol close diklik
            modalTransfer.find('.btn-close').click(function() {
                modalTransfer.modal('hide');
            });

            $(window).click(function(event) {
                if (event.target != modal[0] && !$.contains(modal[0], event.target) && event.target !=
                    catatPemasukanBtn[0] && event.target != catatPengeluaranBtn[0]) {
                    modal.hide();
                }
            });

            typeSelect.change(function() {
                if (typeSelect.val() === '1') {
                    modal.removeClass('bg-expense').addClass('bg-income');
                } else if (typeSelect.val() === '2') {
                    modal.removeClass('bg-income').addClass('bg-expense');
                }

                // Fetch subcategories based on selected type
                var selectedType = typeSelect.val();
                if (selectedType) {
                    $.ajax({
                        url: '/get-subcategories/' + selectedType,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var kategoriSelect = $('#subcategories_id');
                            kategoriSelect.empty();
                            kategoriSelect.append('<option value="">Pilih Kategori</option>');
                            $.each(data, function(key, value) {
                                kategoriSelect.append('<option value="' + value.name +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                }
            });

            // Initialize the background and subcategories based on the default selection
            typeSelect.trigger('change');


        });

        // Memformat angka dengan menambahkan titik sebagai pemisah ribuan dan "IDR" di awal input
        function formatAngka(input) {
            let nilai = input.value;

            // Menghapus semua karakter non-digit
            nilai = nilai.replace(/\D/g, '');

            // Membuat titik setiap 3 digit dari belakang
            nilai = nilai.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Menetapkan nilai yang telah diformat ke input
            input.value = 'IDR ' + nilai;
        }
        // $('#from_account').select2({
        //     ajax: {
        //         url: '/searchKas', // Ganti dengan URL endpoint pencarian data kas Anda
        //         dataType: 'json',
        //         delay: 250,
        //         data: function (params) {
        //             return {
        //                 q: params.term // Parameter pencarian berdasarkan istilah
        //             };
        //         },
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function(obj) {
        //                     return {
        //                         id: obj.id, // ID dari objek yang dipilih
        //                         text: obj.nama_akun, // Text yang akan ditampilkan di dropdown
        //                         jumlah: obj.jumlah // Informasi tambahan, jika perlu
        //                     };
        //                 })
        //             };
        //         },
        //         cache: true
        //     },
        //     placeholder: 'Cari Kas', // Placeholder untuk input pencarian
        //     minimumInputLength: 1, // Minimal karakter yang dibutuhkan sebelum pencarian dimulai
        //     templateResult: function (data) {
        //         if (data.loading) {
        //             return data.text;
        //         }
        //         var $container = $('<div></div>');
        //         $container.text(data.text + ' - ' + data.jumlah); // Menampilkan text dan jumlah di hasil pencarian
        //         return $container;
        //     },
        //     templateSelection: function (data) {
        //         $('#jumlah').val(data.jumlah); // Mengisi otomatis input jumlah dengan nilai dari hasil seleksi
        //         return data.text; // Menampilkan text dari hasil seleksi
        //     }
        // });
        $(document).ready(function() {
            $('.dropdown').on('click', function(e) {
                e.stopPropagation(); // Mencegah klik menutup dropdown
                $(this).find('.dropdown-content').toggle();
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-content').hide(); // Sembunyikan dropdown saat klik di luar
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Handle the click event for the "Edit Transfer" button
            document.querySelectorAll('.edit-transfer-link').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    var transferId = this.getAttribute('data-id');

                    // Use AJAX to fetch the transfer data
                    fetch('/transfers-edit/' + transferId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                alert(data.error);
                            } else {
                                // Populate modal with transfer data
                                document.getElementById('transfer_id').value = data.id;
                                document.getElementById('from_account').value = data
                                    .from_account;
                                document.getElementById('to_account').value = data.to_account;
                                document.getElementById('amount').value = data.amount;
                                document.getElementById('description').value = data.description;

                                document.getElementById('transferModalLabel').textContent =
                                    'Edit Transfer - ID: ' + data.id;
                                document.getElementById('submitButton').textContent =
                                    'Edit Transfer - ID: ' + data.id;
                                // Set form action for update
                                document.getElementById('transferForm').action =
                                    '{{ route('transfers.update', '') }}/' + data.id;

                                // Show the modal
                                var modal = new bootstrap.Modal(document.getElementById(
                                    'transferModal'));
                                modal.show();
                            }
                        });
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Data for accounts (this should be generated server-side and passed to your script)
            var accounts = @json($kas); // Convert PHP variable to JavaScript

            // Handle the click event for the "Transfer" button
            document.getElementById('transferKas').addEventListener('click', function() {
                // Reset the form for new transfer
                document.getElementById('transfer_id').value = ''; // No ID for new transfers
                document.getElementById('from_account').value =
                    '{{ $id_kas->nama_akun }}'; // Pre-fill from_account
                document.getElementById('amount').value = ''; // Clear the amount
                document.getElementById('description').value = ''; // Clear the description

                // Set modal title for create
                document.getElementById('transferModalLabel').textContent = 'Transfer antar kas';
                document.getElementById('submitButton').textContent = 'Transfer';

                // Set form action for creating new transfer
                document.getElementById('transferForm').action =
                    '{{ route('kas_bank.transfer') }}'; // Adjust route name if needed

                // Populate the to_account select field
                var fromAccount = document.getElementById('from_account').value;
                var toAccountSelect = document.getElementById('to_account');

                // Clear previous options
                toAccountSelect.innerHTML = '';

                // Add new options
                accounts.forEach(function(item) {
                    if (item.nama_akun !== fromAccount) {
                        var option = document.createElement('option');
                        option.value = item.nama_akun;
                        option.textContent = item.nama_akun;
                        toAccountSelect.appendChild(option);
                    }
                });

                // Show the modal
                var modal = new bootstrap.Modal(document.getElementById('transferModal'));
                modal.show();
            });
        });
    </script>
@endsection
