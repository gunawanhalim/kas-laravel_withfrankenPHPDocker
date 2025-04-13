@extends('layouts._header')
@section('title', 'Report Daily')

@section('content')

    <head>
        <link rel="stylesheet" type="text/css" href="/assets/akun_bizz/css/pola_20210813ogHiRe.css" />
        <link rel="stylesheet" type="text/css" href="/assets/akun_bizz/css/jquery-ui.min.css" />
        <link rel="stylesheet" type="text/css" href="/assets/akun_bizz/css/jquery-ui.theme.css" />
        <link rel="stylesheet" type="text/css" href="/assets/akun_bizz/css/jquery-ui.structure.min.css" />
        <script type="text/javascript" src="/assets/akun_bizz/js/jquery-1.12.2.min.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/datepicker-id.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/jquery.number.min.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/jquery.iframe-transport.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/jquery.fileupload.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/Chart.min.js"></script>
        <script type="text/javascript" src="/assets/akun_bizz/js/lang_ID2.js"></script>
        {{-- <script type="text/javascript" src="/assets/akun_bizz/js/sidein_2021081341fphB.js"></script> --}}
        <script type="text/javascript" src="/assets/js/chart.min.js"></script>
        <script type="text/javascript" src="/assets/js/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> <!-- Datepicker library -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <!-- Google Analytics Lama -->
        <style>
            .none {
                display: none;
            }

            .trsub {
                padding: 10px;
                background-color: #f9f9f9;
            }

            .detail-table {
                width: 100%;
                border-collapse: collapse;
            }

            .detail-table td {
                border: 1px solid #ccc;
                padding: 5px;
            }
        </style>
        <script>
            (function(i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function() {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-58900972-2', 'auto');
            ga('send', 'pageview');
        </script>
        <!-- Global site tag (gtag.js) - Google Analytics Baru -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-14R49MJD9G"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());

            gtag('config', 'G-14R49MJD9G');
        </script>

    </head>

    <div class="bloktengah" id="blok_kas">
        <div class="kastop">
            <div class="kastitle">
                <div class="mainicon">
                    <img src="/assets/akun_bizz/images/report.png" width="52" height="52" alt="report" />
                </div>
                <div class="kastitlekanan">
                    <div class="judulkas">
                        <select name="akun_kas" id="akun_kas" class="judulkas" onchange="changedate()">
                            <option value="">Pilih Akun Kas</option>
                            @foreach ($kas as $q)
                                <option value="{{ $q->nama_akun }}">{{ $q->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="desckas">
                        Laporan Harian
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div id="downloadfile" class="download">
                <img src="/assets/akun_bizz/images/export_xls_grey.png" width="24" height="24" alt="xls" />
                <a id="downloadLink" href="#" target="_blank" onclick="handleDownload(event)">
                    <img src="/assets/akun_bizz/images/export_pdf.png" width="24" height="24" alt="xls"
                        title="Download halaman ini dalam format PDF" />
                </a>
            </div>
            <div class="clear"></div>
        </div>
        <div class="kasbody" id="bodyreport">
            <div class="bodyreport">
                <div class="selectcashbar">
                    <input type="hidden" id="monthlinkkas" value="&tab=general" />
                    <div class="reportbulanarea">
                        <div class="bulankastgl bulankas" id="bulankasreport">
                            <input name="lap_tgl" type="text" class="lap_tgl datepicker" id="lap_tgl" size="15"
                                title="Klik untuk mengganti tanggal" onchange="changedate()" />
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all"
                        role="tablist">
                        <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active">
                            <a href="#tab_umum" class="ui-tabs-anchor">Umum</a>
                        </li>
                        <li class="ui-state-default ui-corner-top">
                            <a href="#tab_aktifitas" class="ui-tabs-anchor">Aktivitas</a>
                        </li>
                        <li class="ui-state-default ui-corner-top">
                            <a href="#tab_transfer" class="ui-tabs-anchor">Transfer</a>
                        </li>
                    </ul>
                    <div id="tab_umum" class="ui-tabs-panel ui-widget-content ui-corner-bottom reportarea">
                        <div class="reportbox">
                            <h3 class="h3report" id="nama_akun_umum">
                            </h3>
                            <div class="reportleft" id="allcash">
                                <div class="thediv">
                                    <table class="tabreport">
                                        <tr>
                                            <td class="tdgray">Saldo Awal Hari</td>
                                            <td class="center tdplusmin tdgray">&nbsp;</td>
                                            <td class="right tdmatauang tdgray">Rp.</td>
                                            <td class="right tduang tdgray" id="saldo_awal">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="center tdplusmin">&nbsp;</td>
                                            <td class="right tdmatauang">&nbsp;</td>
                                            <td class="right tduang">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="listmasuk">Semua Pemasukan</td>
                                            <td class="right tdplusmin listmasuk">(+)</td>
                                            <td class="right tdmatauang listmasuk">Rp.</td>
                                            <td class="right tduang listmasuk" id="saldo_pemasukan">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="listkeluar">Semua Pengeluaran</td>
                                            <td class="center tdplusmin listkeluar">(-)</td>
                                            <td class="right tdmatauang listkeluar">Rp.</td>
                                            <td class="right tduang listkeluar" id="saldo_pengeluaran">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="right line">Akumulasi</td>
                                            <td class="right line tdplusmin">&nbsp;</td>
                                            <td class="right line tdmatauang">Rp.</td>
                                            <td class="right line tduang" id="akumulasi_tanggal">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="tdplusmin">&nbsp;</td>
                                            <td class="right tdmatauang">&nbsp;</td>
                                            <td class="right tduang">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td class="tdgray">Saldo Akhir Hari</td>
                                            <td class="tdgray">&nbsp;</td>
                                            <td class="right tdmatauang tdgray">Rp.</td>
                                            <td class="right tduang tdgray" id=saldo_akhir></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="reportright">
                                {{-- <canvas id="allcashcart" height="340"></canvas> --}}
                                <canvas id="myChart" width="400" height="200"></canvas>

                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="reportbox">
                            <div class="reportleft">
                                <h3 class="h3report">
                                    <img src="/assets/akun_bizz/images/list-keluar.png" width="20" height="20"
                                        alt="expense" />&nbsp;
                                    Pengeluaran
                                </h3>
                                <div class="exin" id="ex_exin">
                                    <table border="0" class="tabreport">
                                        <tbody id="kategori_pengeluaran_container">
                                            <!-- Baris kategori pengeluaran akan ditambahkan di sini -->
                                        </tbody>
                                        <tr>
                                            <td class="line">&nbsp;</td>
                                            <td class="right tdmatauang line">Rp.</td>
                                            <td class="right tduang line" id="total_pengeluaran"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="reportright">
                                <h3 class="h3report">
                                    <img src="/assets/akun_bizz/images/list-masuk.png" width="20" height="20"
                                        alt="income" />&nbsp;
                                    Pemasukan
                                </h3>

                                <div class="exin" id="in_exin">
                                    <table border="0" class="tabreport">
                                        <tbody id="kategori_pemasukan_container">
                                            <!-- Baris kategori pemasukan akan ditambahkan di sini -->
                                        </tbody>
                                        <tr>
                                            <td class="line">&nbsp;</td>
                                            <td class="right tdmatauang line">Rp.</td>
                                            <td class="right tduang line" id="total_pemasukan"></td>
                                        </tr>
                                    </table>
                                </div>

                            </div>
                            <div class="clear"></div>
                        </div>
                        <small>** Kategori tanpa aktivitas disembunyikan.</small>

                        <script type="text/javascript">
                            // Inisialisasi datepicker dengan format tanggal
                            flatpickr('.datepicker', {
                                dateFormat: 'Y-m-d',
                                defaultDate: new Date()
                            });

                            // Fungsi untuk memuat data chart
                            function loadChart(data) {
                                const ctx = document.getElementById('myChart').getContext('2d');
                                if (window.myChart) {
                                    window.myChart.destroy(); // Hapus chart jika sudah ada
                                }

                                // Buat chart baru
                                window.myChart = new Chart(ctx, {
                                    type: 'bar',
                                    data: data,
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            }
                        </script>
                    </div>
                </div>
                <!-- Tab Content for Aktivitas -->
                <div id="tab_aktifitas" class="ui-tabs-panel ui-widget-content ui-corner-bottom reportarea"
                    style="display: none;">
                    <div class="reportbox">
                        <h3 class="h3report kapital" id="nama_akun_aktifitas_pengeluaran">
                        </h3>
                        <table border="0" class="tabreport">
                            <tbody id="kategori_keluar_per_tanggal">

                            </tbody>
                            <tr>
                                <td class="line">&nbsp;</td>
                                <td class="line">&nbsp;</td>
                                <td class="right tdmatauang line">Rp.</td>
                                <td class="right tduang line" id="totalpengeluarantanggal"></td>
                            </tr>
                        </table>
                    </div>
                    {{-- Pemasukan Per Tanggal --}}
                    <div class="reportbox">
                        <h3 class="h3report kapital" id="nama_akun_aktifitas_pemasukan">
                        </h3>
                        <table border="0" class="tabreport">
                            <tbody id="kategori_masuk_per_tanggal">

                            </tbody>
                            <tr>
                                <td class="line">&nbsp;</td>
                                <td class="line">&nbsp;</td>
                                <td class="right tdmatauang line">Rp.</td>
                                <td class="right tduang line" id="totalpemasukantanggal"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- Tab Content for Transfer -->
                <div id="tab_transfer" class="ui-tabs-panel ui-widget-content ui-corner-bottom reportarea"
                    style="display: none;">
                    <div class="reportbox">
                        <h3 class="h3report kapital" id="nama_akun_transfer">
                        </h3>

                        <table border="0" class="tabreport">
                            <tbody id="kategori_transfer">

                            </tbody>
                            <tr>
                                <td class="line">&nbsp;</td>
                                <td class="right line">TOTAL</td>
                                <td class="tdplusmin center line">&nbsp;</td>
                                <td class="right line tdmatauang">Rp</td>
                                <td class="right line tduang" id="total_transfer_masuk"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

            <div class="popdownmail" id="popdownmail">

                <form method="post" name="mailattach" id="mailattach" onsubmit="return attach_mail(event)">
                    <div class="invmailfrom">

                        Dari*<br />
                        <input type="text" style="width: 240px;" name="atfromname" id="atfromname"
                            placeholder="Nama Anda" value="Gunawan Halim" /><br />
                        <input type="text" style="width: 240px;" name="atfrom" id="atfrom"
                            placeholder="Email Anda" value="gunawanhalim17@gmail.com" /><br /><br />
                    </div>

                    <div class="invmailto">
                        Ke*<br />
                        <input type="text" style="width: 240px;" name="attoname" id="attoname"
                            placeholder="Nama Penerima" /><br />
                        <input type="text" style="width: 240px;" name="atto" id="atto"
                            placeholder="Email Penerima" /><br /><br />
                    </div>
                    <div class="clear"></div>

                    <div class="invmailbody">
                        Konten Email*<br />
                        <input type="text" style=" width:98%;" name="atmailsubject" id="atmailsubject"
                            placeholder="Subjek" /><br />
                        <textarea name="atmailmessage" style="width:98%; height: 96px;" id="atmailmessage" placeholder="Pesan Anda"></textarea>
                    </div>
                    <div class="atchoosefile">
                        Lampiran<br />
                        <input type="radio" name="pilihfile" id="choosefile" value="xls" /> Excel
                        <input type="radio" name="pilihfile" value="pdf" /> PDF
                    </div>
                    <div class="invmailbutton">
                        <input type="hidden" id="keyword" value="report" />
                        <input type="hidden" id="lap_tipe" value="daily" />
                        <input type="hidden" id="lap_bulan" value="" />
                        <input type="hidden" id="lap_tahun" value="" />
                        <input type="hidden" id="lap_tgl" value="" />
                        <input type="hidden" id="lap_tglto" value="" />
                        <input type="hidden" id="lap_tglfrom" value="" />
                        <input type="hidden" id="lap_tab" value="general" />
                        <input type="hidden" id="lap_buku" value="" />
                        <img class="sendmail_loader" id="sendmail_loader" src="/assets/akun_bizz/images/loader_blue.gif"
                            width="32" height="32" alt="loader" />
                        <input type="button" id="atmailcancel" onClick="mailfile()" value="Batal" />
                        &nbsp;&nbsp;
                        <input type="button" id="atmailsend" class="invact invsend" onClick="attach_mail()"
                            value="Kirim Sekarang!" title="Kirim email beserta lampiran e-Invoice" />
                    </div>
                </form>

                <div class="notif" id="sendmail_notif"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <script type="text/javascript">
        function changedate() {
            const date = document.getElementById('lap_tgl').value;
            const namaAkun = document.getElementById('akun_kas').value;

            fetch(`/report_daily/get?nama_akun=${encodeURIComponent(namaAkun)}&date=${encodeURIComponent(date)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update displayed data
                    document.getElementById('saldo_akhir').innerText = data.saldo_akhir;
                    document.getElementById('saldo_awal').innerText = data.saldo_awal;
                    document.getElementById('akumulasi_tanggal').innerText = data.akumulasi;
                    document.getElementById('saldo_pemasukan').innerText = data.jumlah_pemasukan;
                    document.getElementById('saldo_pengeluaran').innerText = data.jumlah_pengeluaran;
                    document.getElementById('nama_akun_aktifitas_pengeluaran').innerHTML =
                        `<img src="/assets/akun_bizz/images/list-keluar.png" width="20" height="20" alt="expense" /> - ${data.nama_akun_kecil}`;
                    document.getElementById('nama_akun_aktifitas_pemasukan').innerHTML =
                        `<img src="/assets/akun_bizz/images/list-masuk.png" width="20" height="20" alt="expense" /> - ${data.nama_akun_kecil}`;
                    document.getElementById('nama_akun_umum').innerHTML =
                        `<img src="/assets/akun_bizz/images/kas_icon_01_small.png" width="20" height="20" alt="expense" /> - ${data.nama_akun_kecil}`;
                    document.getElementById('nama_akun_transfer').innerHTML =
                        `<img src="/assets/akun_bizz/images/list-transfer.png" width="20" height="20" alt="expense" /> - ${data.nama_akun_kecil}`;
                    // Clear and populate kategori containers
                    const kategoriPemasukanContainer = document.getElementById('kategori_pemasukan_container');
                    kategoriPemasukanContainer.innerHTML = '';

                    const kategoriPengeluaranContainer = document.getElementById('kategori_pengeluaran_container');
                    kategoriPengeluaranContainer.innerHTML = '';

                    const kategoriPemasukanPerTanggalContainer = document.getElementById('kategori_masuk_per_tanggal');
                    kategoriPemasukanPerTanggalContainer.innerHTML = '';

                    const kategoriPengeluaranPerTanggalContainer = document.getElementById(
                        'kategori_keluar_per_tanggal');
                    kategoriPengeluaranPerTanggalContainer.innerHTML = '';
                    // Populate kategori pemasukan
                    let totalPemasukan = 0;
                    for (const [kategori, total] of Object.entries(data.kategori_pemasukan)) {
                        totalPemasukan += total;
                        const row = `<tr>
                        <td>${kategori}</td>
                        <td class="right tdmatauang">Rp.</td>
                        <td class="right tduang">${total}</td>
                    </tr>`;
                        kategoriPemasukanContainer.insertAdjacentHTML('beforeend', row);
                    }
                    document.getElementById('total_pemasukan').innerText = totalPemasukan;

                    // Populate kategori pengeluaran
                    let totalPengeluaran = 0;
                    for (const [kategori, total] of Object.entries(data.kategori_pengeluaran)) {
                        totalPengeluaran += total;
                        const row = `<tr>
                        <td>${kategori}</td>
                        <td class="right tdmatauang">Rp.</td>
                        <td class="right tduang">${total}</td>
                    </tr>`;
                        kategoriPengeluaranContainer.insertAdjacentHTML('beforeend', row);
                    }
                    document.getElementById('total_pengeluaran').innerText = totalPengeluaran;
                    // Kategori Pengeluaran Per Tanggal
                    // Pastikan kontainer ada sebelum melanjutkan
                    if (!kategoriPengeluaranPerTanggalContainer) {
                        console.error("Kontainer 'kategori_keluar_per_tanggal' tidak ditemukan!");
                    } else {
                        // Kosongkan kontainer sebelum mengisi data baru
                        kategoriPengeluaranPerTanggalContainer.innerHTML = '';

                        // Variabel untuk menyimpan total pemasukan
                        let totalPengeluaranTanggal = 0;

                        // Pastikan data tersedia dan memiliki struktur yang benar
                        if (data.kategori_pengeluaran_tanggal && typeof data.kategori_pengeluaran_tanggal ===
                            'object') {
                            // Iterasi data berdasarkan kategori
                            for (const [kategori, dates] of Object.entries(data.kategori_pengeluaran_tanggal)) {
                                // Hitung total jumlah per kategori
                                let totalKategori = Object.values(dates).reduce((sum, jumlah) => sum + jumlah, 0);

                                // Perbarui total pemasukan global
                                totalPengeluaranTanggal += totalKategori;

                                // Buat baris untuk kategori dengan total jumlah
                                const kategoriRow = `
                                                <tr>
                                                    <td class="center" style="width: 20px;">
                                                        <img class="folderreport" src="/assets/akun_bizz/images/folder.png"
                                                            width="20" height="15" alt="folder" title="Klik untuk melihat detailnya"
                                                            onclick="slidedetail('${kategori.replace(/\s+/g, '_')}')" />
                                                    </td>
                                                    <td>
                                                        <span class="pointer hover" onclick="slidedetail('${kategori.replace(/\s+/g, '_')}')">
                                                            ${kategori} </span>
                                                    </td>
                                                    <td class="right tdmatauang">Rp.</td>
                                                    <td class="right tduang">${totalKategori.toLocaleString('id-ID')}</td>
                                                </tr>
                                                <tr class="none" id="trsub_${kategori.replace(/\s+/g, '_')}">
                                                    <td>&nbsp;</td>
                                                    <td colspan="3">
                                                        <div class="trsub" id="divsub_${kategori.replace(/\s+/g, '_')}"></div>
                                                    </td>
                                                </tr>`;

                                // Masukkan baris kategori ke dalam kontainer
                                kategoriPengeluaranPerTanggalContainer.insertAdjacentHTML('beforeend', kategoriRow);

                                // Buat rincian tanggal untuk kategori ini
                                let rincianHTML = '<table class="">';
                                for (const [tanggal, jumlah] of Object.entries(dates)) {
                                    rincianHTML += `
                                    <tr>
                                        <td style="width: 500px;">${tanggal}</td>
                                        <td class="right tduang">Rp. ${jumlah.toLocaleString('id-ID')}</td>
                                    </tr>`;
                                }
                                rincianHTML += '</table>';

                                // Masukkan rincian ke dalam div untuk kategori ini
                                document.getElementById(`divsub_${kategori.replace(/\s+/g, '_')}`).innerHTML =
                                    rincianHTML;
                            }
                        } else {
                            alert("Data 'kategori_pengeluaran_tanggal' tidak valid atau kosong!");
                        }

                        // Tampilkan total pemasukan di elemen yang sesuai
                        const totalPengeluaranTanggalElement = document.getElementById('totalpengeluarantanggal');
                        if (totalPengeluaranTanggalElement) {
                            totalPengeluaranTanggalElement.innerText = totalPengeluaranTanggal.toLocaleString('id-ID');
                        } else {
                            console.error("Elemen 'totalpengeluarantanggal' tidak ditemukan!");
                        }
                    }
                    // Kategori Pemasukan Per Tanggal
                    // Pastikan kontainer ada sebelum melanjutkan
                    if (!kategoriPemasukanPerTanggalContainer) {
                        console.error("Kontainer 'kategori_masuk_per_tanggal' tidak ditemukan!");
                    } else {
                        // Kosongkan kontainer sebelum mengisi data baru
                        kategoriPemasukanPerTanggalContainer.innerHTML = '';

                        // Variabel untuk menyimpan total pemasukan
                        let totalPemasukanTanggal = 0;

                        // Pastikan data tersedia dan memiliki struktur yang benar
                        if (data.kategori_pemasukan_tanggal && typeof data.kategori_pemasukan_tanggal === 'object') {
                            // Iterasi data berdasarkan kategori
                            for (const [kategori, dates] of Object.entries(data.kategori_pemasukan_tanggal)) {
                                // Hitung total jumlah per kategori
                                let totalKategori = Object.values(dates).reduce((sum, jumlah) => sum + jumlah, 0);

                                // Perbarui total pemasukan global
                                totalPemasukanTanggal += totalKategori;

                                // Buat baris untuk kategori dengan total jumlah
                                const kategoriRow = `
                                                <tr>
                                                    <td class="center" style="width: 20px;">
                                                        <img class="folderreport" src="/assets/akun_bizz/images/folder.png"
                                                            width="20" height="15" alt="folder" title="Klik untuk melihat detailnya"
                                                            onclick="slidedetail('${kategori.replace(/\s+/g, '_')}')" />
                                                    </td>
                                                    <td>
                                                        <span class="pointer hover" onclick="slidedetail('${kategori.replace(/\s+/g, '_')}')">
                                                            ${kategori} </span>
                                                    </td>
                                                    <td class="right tdmatauang">Rp.</td>
                                                    <td class="right tduang">${totalKategori.toLocaleString('id-ID')}</td>
                                                </tr>
                                                <tr class="none" id="trsub_${kategori.replace(/\s+/g, '_')}">
                                                    <td>&nbsp;</td>
                                                    <td colspan="3">
                                                        <div class="trsub" id="divsub_${kategori.replace(/\s+/g, '_')}"></div>
                                                    </td>
                                                </tr>`;

                                // Masukkan baris kategori ke dalam kontainer
                                kategoriPemasukanPerTanggalContainer.insertAdjacentHTML('beforeend', kategoriRow);

                                // Buat rincian tanggal untuk kategori ini
                                let rincianHTML = '<table class="">';
                                for (const [tanggal, jumlah] of Object.entries(dates)) {
                                    rincianHTML += `
                                    <tr>
                                        <td style="width: 500px;">${tanggal}</td>
                                        <td class="right tduang">Rp. ${jumlah.toLocaleString('id-ID')}</td>
                                    </tr>`;
                                }
                                rincianHTML += '</table>';

                                // Masukkan rincian ke dalam div untuk kategori ini
                                document.getElementById(`divsub_${kategori.replace(/\s+/g, '_')}`).innerHTML =
                                    rincianHTML;
                            }
                        } else {
                            alert("Data 'kategori_pemasukan_tanggal' tidak valid atau kosong!");
                        }

                        // Tampilkan total pemasukan di elemen yang sesuai
                        const totalPemasukanTanggalElement = document.getElementById('totalpemasukantanggal');
                        if (totalPemasukanTanggalElement) {
                            totalPemasukanTanggalElement.innerText = totalPemasukanTanggal.toLocaleString('id-ID');
                        } else {
                            console.error("Elemen 'totalpemasukantanggal' tidak ditemukan!");
                        }
                    }


                    drawChart(data.jumlah_pemasukan, data.jumlah_pengeluaran);
                })
                .catch(error => {
                    console.error('Ada masalah dengan permintaan:', error);
                });
        }

        let myChart; // Variabel untuk menyimpan instance chart

        function drawChart(jumlahPemasukan, jumlahPengeluaran) {
            const ctx = document.getElementById('myChart').getContext('2d');

            // Jika chart sudah ada, hancurkan sebelum menggambar yang baru
            if (myChart) {
                myChart.destroy();
            }

            myChart = new Chart(ctx, {
                type: 'bar', // Jenis chart (bar, line, pie, dll.)
                data: {
                    labels: ['Pemasukan', 'Pengeluaran'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [jumlahPemasukan, Math.abs(
                            jumlahPengeluaran)], // Menggunakan Math.abs untuk pengeluaran
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Panggil fungsi untuk mengambil data hari ini saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            const today = new Date().toISOString().split('T')[
                0]; // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
            document.getElementById('lap_tgl').value = today; // Set input tanggal ke hari ini
            changedate(); // Panggil fungsi untuk mengambil data
        });

        $(document).ready(function(e) {
            $('#loadnotif').fadeOut(500);
        });
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.ui-tabs-nav a');
            const tabContents = document.querySelectorAll('.ui-tabs-panel');

            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active state from all tabs and hide all content panels
                    tabs.forEach(t => t.parentElement.classList.remove('ui-tabs-active',
                        'ui-state-active'));
                    tabContents.forEach(content => content.style.display = 'none');

                    // Activate the clicked tab and show the corresponding content panel
                    tab.parentElement.classList.add('ui-tabs-active', 'ui-state-active');
                    const contentId = this.getAttribute('href');
                    document.querySelector(contentId).style.display = 'block';
                });
            });

            // Show default tab (Umum) on page load
            document.querySelector('#tab_umum').style.display = 'block';
        });
        // Fungsi untuk menampilkan atau menyembunyikan rincian
        function slidedetail(id) {
            const detailRow = document.getElementById(`trsub_${id}`);
            if (detailRow) {
                detailRow.classList.toggle('none'); // Toggle visibility
            } else {
                console.error(`Detail dengan ID '${id}' tidak ditemukan!`);
            }
        }
    </script>

@endsection
