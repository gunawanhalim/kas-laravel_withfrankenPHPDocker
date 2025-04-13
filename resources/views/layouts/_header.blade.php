<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Buku Kas</title>
    <!-- plugins:css -->
    <link href="{{ asset('node_modules/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" href="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">

    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo"
                    href="/beranda.php"><!--<img src="../../assets/images/logo-mini.svg" alt="logo" />--> Kas</a>
                <a class="navbar-brand brand-logo-mini"
                    href="/beranda.php"><!--<img src="../../assets/images/logo-mini.svg" alt="logo" />-->Kas</a>
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>
                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item  dropdown d-none d-md-block">
                        <a class="nav-link dropdown-toggle" id="reportDropdown" href="#" data-toggle="dropdown"
                            aria-expanded="false"> Reports </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="reportDropdown">
                            <a class="dropdown-item" href="{{ url('/export-pdf') }}" target="_blank">
                                <i class="mdi mdi-file-pdf mr-2"></i>PDF </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/export-excel') }}">
                                <i class="mdi mdi-file-excel mr-2"></i>Excel </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/export-doc') }}">
                                <i class="mdi mdi-file-word mr-2"></i>doc </a>
                        </div>
                    </li>
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown"
                            aria-expanded="false">
                            <div class="nav-profile-img">
                                <img src="../../assets/images/faces/face28.png" alt="image">
                            </div>
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black">{{ Auth::user()->name }}</p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown dropdown-menu-right p-0 border-0 font-size-sm"
                            aria-labelledby="profileDropdown" data-x-placement="bottom-end">
                            <div class="p-3 text-center bg-primary">
                                <img class="img-avatar img-avatar48 img-avatar-thumb"
                                    src="../../assets/images/faces/face28.png" alt="">
                            </div>
                            <div class="p-2">
                                <h5 class="dropdown-header text-uppercase pl-2 text-dark">User Options</h5>
                                <a class="dropdown-item py-1 d-flex align-items-center justify-content-between"
                                    href="/user-edit-detail">
                                    <span>Profile</span>
                                    <i class="mdi mdi-settings"></i>
                                </a>
                                <div role="separator" class="dropdown-divider"></div>
                            </div>
                        </div>
                    </li>
                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                    data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-category">Page</li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/beranda.php">
                            <span class="icon-bg"><i class="mdi mdi-cube menu-icon"></i></span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                            aria-controls="ui-basic">
                            <span class="icon-bg"><i class="mdi mdi-book-open"></i></span>
                            <span class="menu-title">Buku kas</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-basic">
                            @foreach ($kas as $q)
                                @if ($q->tampil == 'y')
                                    <ul class="nav flex-column sub-menu">
                                        <li class="nav-item">
                                            <a class="nav-link"
                                                href="/akun_kas/{{ $q->nama_akun }}">{{ $q->nama_akun }}</a>
                                        </li>
                                    </ul>
                                @endif
                            @endforeach
                        </div>
                    </li>
                    {{-- Pelanggan --}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#pelanggan" aria-expanded="false"
                            aria-controls="pelanggan">
                            <span class="icon-bg"><i class="mdi mdi-lock menu-icon"></i></span>
                            <span class="menu-title">Pelanggan</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="pelanggan">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item"> <a class="nav-link"
                                        href="{{ route('pelangganPenjualan.index') }}"> Pelanggan Penjualan </a></li>
                                {{-- <li class="nav-item"> <a class="nav-link" href="{{route('pelangganPembelian.index')}}">Pelanggan Pembelian </a></li> --}}
                            </ul>
                        </div>
                    </li>
                    {{-- End Pelanggan --}}
                    {{-- Utang Pituang --}}
                    {{-- <li class="nav-item">
              <a class="nav-link" href="{{ route('piutang.index') }}">
                <span class="icon-bg"><i class="mdi mdi-credit-card-multiple"></i></span>
                <span class="menu-title">Piutang</span>
              </a>
            </li> --}}
                    {{-- <li class="nav-item">
              <a class="nav-link" data-toggle="collapse" href="#piutang" aria-expanded="false" aria-controls="piutang">
                <span class="icon-bg"><i class="mdi mdi-credit-card-multiple"></i></span>
                <span class="menu-title">Utang Piutang</span>
                <i class="menu-arrow"></i>
              </a>
              <div class="collapse" id="piutang">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                          <a class="nav-link" href="{{ route('debit') }}">Utang</a>
                          <a class="nav-link" href="{{ route('credit') }}">Piutang</a>
                        </li>
                    </ul>
              </div>
            </li> --}}
                    {{-- End Piutang --}}
                    {{-- Hutang Piutang --}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#hutang_piutang" aria-expanded="false"
                            aria-controls="hutang_piutang">
                            <span class="icon-bg"><i class="mdi mdi-lock menu-icon"></i></span>
                            <span class="menu-title">Hutang Piutang</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="hutang_piutang">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('utang.index') }}"> Hutang </a>
                                    <ul>
                                        @foreach ($linkCategori as $supplier)
                                            {{-- @if ($supplier->kategori == 'Utang') --}}
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="/link-kategori/{{ urlencode($supplier->name) }}"> Kategori
                                                    {{ $supplier->name }} </a>
                                            </li>
                                            {{-- @endif --}}
                                        @endforeach
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('piutang.index') }}"> Piutang </a>
                                    <ul>
                                        @foreach ($linkCategori as $supplier)
                                            {{-- @if ($supplier->kategori == 'Piutang') --}}
                                            <li class="nav-item">
                                                <a class="nav-link"
                                                    href="/link-kategori-piutang/{{ urlencode($supplier->name) }}">
                                                    Kategori {{ $supplier->name }} </a>
                                            </li>
                                            {{-- @endif --}}
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{-- End Hutang Piutang --}}
                    {{-- Penjualan --}}
                    <li class="nav-item">
                        <a class="nav-link" href="/penjualan">
                            <span class="icon-bg"><i class="mdi mdi-target"></i></span>
                            <span class="menu-title">Penjualan</span>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
              <a class="nav-link" href="{{ route('utang.index') }}">
                <span class="icon-bg"><i class="mdi mdi-credit-card-multiple"></i></span>
                <span class="menu-title">Utang</span>
              </a>
            </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pembelian.index') }}">
                            <span class="icon-bg"><i class="mdi mdi-target"></i></span>
                            <span class="menu-title">Pembelian</span>
                        </a>
                    </li>
                    {{-- End Penjualan --}}
                    {{-- <li class="nav-item mb-2">
              <a class="nav-link" href="/laporan.php">
                <span class="icon-bg"><i class="mdi mdi-book-multiple-variant"></i></span>
                <span class="menu-title">Laporan Kas</span>
              </a> --}}
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#report-basic" aria-expanded="false"
                            aria-controls="report-basic">
                            <span class="icon-bg"><i class="mdi mdi-book-multiple-variant"></i></span>
                            <span class="menu-title">Laporan Kas</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="report-basic">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('report_daily') }}">Harian</a>
                                    <a class="nav-link" href="/bulanan?">Bulanan</a>
                                    <a class="nav-link" href="/tahunan?">Tahunan</a>
                                </li>
                            </ul>
                        </div>
                        {{-- <li class="nav-item mb-2">
              <a class="nav-link" href="/pelunasan.php">
                <span class="icon-bg"><i class="mdi mdi-book-multiple-variant"></i></span>
                <span class="menu-title">Pelunasan</span>
              </a> --}}
                        {{-- <li class="nav-item sidebar-user-actions">
              <div class="sidebar-user-menu">
              <div class="user-details">
                @if (Auth::user()->role == 'Owner')
                <a href="#" class="nav-link"><i class="mdi mdi mdi-account-box menu-icon"></i>
                  <span class="menu-title">User</span>
                </a>
                @endif
              </div>
              </div>
            </li> --}}
                        {{-- Settings --}}
                    <li class="nav-item ">
                        <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false"
                            aria-controls="settings">
                            <span class="icon-bg"><i class="mdi mdi-settings menu-icon"></i></span>
                            <span class="menu-title">Settings</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="settings">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="/e-Invoice">E-Invoice</a>
                                    <a class="nav-link" href="/showKategori">Kategori</a>
                                    <a class="nav-link" href="/user-edit-detail">Akun Saya</a>
                                    @if (Auth::user()->role == 'Owner' || Auth::user()->role == 'Manager')
                                        <a class="nav-link" href="/multiuser.php">Multi User</a>
                                    @endif
                                    {{-- <a class="nav-link" href="{{ route('transfer.index') }}">History Trwansfer</a> --}}
                                </li>
                            </ul>
                        </div>
                    </li>
                    {{-- End Settings --}}
                    {{-- Logout --}}
                    <li class="nav-item sidebar-user-actions">
                        <div class="sidebar-user-menu">
                            <a href="{{ route('logout') }}" class="nav-link"><i
                                    class="mdi mdi-logout menu-icon"></i>
                                <span class="menu-title">Keluar</span></a>
                        </div>
                    </li>
                </ul>
                {{-- End Logout --}}
            </nav>
            <!-- partial -->
            <div class="main-panel">

                <!-- content-wrapper ends -->
                <div class="content-wrapper">
                    @yield('content')
                </div>

                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="footer-inner-wraper">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                                Aplikasi kas 2024</span>
                            {{-- <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap dashboard templates</a> from Bootstrapdash.com</span> --}}
                        </div>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../assets/vendors/jquery-circle-progress/js/circle-progress.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/misc.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <!-- End custom js for this page -->
</body>

</html>
