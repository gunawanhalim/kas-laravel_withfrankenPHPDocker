@extends('layouts._header')
@section('title','Laporan Kas')

@section('content')
{{-- <link rel="stylesheet" href="assets/css/datepicker.min.css"> --}}

<i id="bannerClose"></i>
            <div class="row">
                <div class="col-md-12">
                <div class="d-sm-flex justify-content-between align-items-center transaparent-tab-border {">
                  <ul class="nav nav-tabs tab-transparent" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="harian-tab" data-toggle="tab" href="#harian-1" role="tab" aria-selected="false">Laporan Kas</a>
                    </li>
                    {{-- <li class="nav-item">
                      <a class="nav-link" id="mingguan-tab" data-toggle="tab" href="" role="tab" aria-selected="true">Mingguan</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="performance-tab" data-toggle="tab" href="#" role="tab" aria-selected="false">Bulanan</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="conversion-tab" data-toggle="tab" href="#" role="tab" aria-selected="false">Tahunan</a>
                    </li> --}}
                  </ul>>
                </div>
                <div class="tab-content tab-transparent-content">
                  <div class="tab-pane fade show active" id="harian-1" role="tabpanel" aria-labelledby="harian-tab">
                    <div class="row">
                      <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body text-center">
                            <h5 class="mb-2 text-dark font-weight-normal">Total Kas</h5>
                            <h2 class="mb-4 text-dark font-weight-bold" id="total-kas"></h2>
                            <div class="dashboard-progress harian-kas-1 d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-book-plus icon-md absolute-center text-dark"></i></div>
                            <p class="mt-4 mb-0">
                                Meningkat sejak kemarin</p>
                            <h3 class="mb-0 font-weight-bold mt-2 text-dark" id="total-entries">Total Pemasukan dan Pengeluaran: 0</h3>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body text-center">
                            <h5 class="mb-2 text-dark font-weight-normal">Pemasukan</h5>
                            <h2 class="mb-4 text-dark font-weight-bold" id="pemasukan"></h2>
                            <div class="dashboard-progress dashboard-progress-2 d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-account-circle icon-md absolute-center text-dark"></i></div>
                            <p class="mt-4 mb-0">Meningkat sejak kemarin</p>
                            <h3 class="mb-0 font-weight-bold mt-2 text-dark">50%</h3>
                          </div>
                        </div>
                        </div>
                            <div class="col-xl-3 col-lg-6 col-sm-6 grid-margin stretch-card">
                              <div class="card">
                                <div class="card-body text-center">
                                  <h5 class="mb-2 text-dark font-weight-normal">Pengeluaran</h5>
                                  <h2 class="mb-4 text-dark font-weight-bold" id="pengeluaran"></h2>
                                  <div class="dashboard-progress dashboard-progress-3 d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-cube icon-md absolute-center text-dark"></i></div>
                                  <p class="mt-4 mb-0">Menurun sejak kemarin</p>
                                  <h3 class="mb-0 font-weight-bold mt-2 text-dark">25%</h3>
                                </div>
                              </div>
                            </div>
                      <div class="col-xl-3  col-lg-6 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                          <div class="card-body text-center">
                            <h5 class="mb-2 text-dark font-weight-normal">Total Penjualan</h5>
                            <h2 class="mb-4 text-dark font-weight-bold" id="totalPenjualan"></h2>
                            <div class="dashboard-progress dashboard-progress-4 d-flex align-items-center justify-content-center item-parent"><i class="mdi mdi-eye icon-md absolute-center text-dark"></i></div>
                            <p class="mt-4 mb-0">Increased since yesterday</p>
                            <h3 class="mb-0 font-weight-bold mt-2 text-dark">35%</h3>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 grid-margin">
                        <div class="card">
                          <div class="card-body">
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                  <h5 class="card-title mb-0">Aktivitas</h5>
                                  <div class="dropdown dropdown-arrow-none">
                                    <button class="btn p-0 text-dark dropdown-toggle" type="button" id="dropdownMenuIconButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="mdi mdi-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuIconButton1">
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
                              <div class="col-lg-6 col-sm-6 grid-margin  grid-margin-lg-0">
                                <div class="wrapper pb-5 border-bottom">
                                  <div class="text-wrapper d-flex align-items-center justify-content-between mb-2">
                                    <p class="mb-0 text-dark">Halaman Analisis</p>
                                    <div>
                                        <input type="text" id="start-date" placeholder="Start Date" class="datepicker">
                                        <input type="text" id="end-date" placeholder="End Date" class="datepicker">
                                        <input type="text" id="searchAnalisis" placeholder="Search Name Account">
                                        <button id="filter-analisis">Filter</button>
                                    </div>
                                  </div>
                                  <div class="graph-custom-legend primary-dot" id="pageViewAnalyticLengend"></div>
                                  <canvas id="page-view-analytic"></canvas>
                                </div>
                              </div>
                              <div class="col-lg-6 col-sm-6 grid-margin  grid-margin-lg-0">
                                <div class="pl-0 pl-lg-4 ">
                                  <div class="d-xl-flex justify-content-between align-items-center mb-2">
                                    <div class="d-lg-flex align-items-center mb-lg-2 mb-xl-0">
                                      <h3 class="text-dark font-weight-bold mr-2 mb-0">Rincian</h3>
                                    </div>
                                    <div class="d-lg-flex">
                                      <p class="mr-2 mb-0">Timezone:</p>
                                      <p class="text-dark font-weight-bold mb-0">Asia/Makassar</p>
                                    </div>
                                  </div>
                                <div>
                                  <input type="text" id="startDate" name="start_date" placeholder="Pilih tanggal mulai..." class="datepicker mb-2">                                    
                                  <input type="text" id="endDate" name="end_date" placeholder="Pilih tanggal Akhir..." class="datepicker mb-2">                                    
                                  <input type="text" id="searchData" name="search" placeholder="Masukkan nama akun...">                                    
                                  <button id="filter-data">Filter</button>
                                    {{-- <button class="" id="download-pdf" target="_blank"><i class="mdi mdi-file-pdf mr-2"></i>PDF </button> --}}
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
                </div>
              </div>
            </div>
        <!-- main-panel ends -->
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/jquery-circle-progress/js/circle-progress.min.js"></script>

    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/datepicker.min.js"></script>
    @endsection