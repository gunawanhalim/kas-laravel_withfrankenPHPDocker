
<!DOCTYPE html>
<html lang="en, id">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Laporan Kas_{{$tanggal}}
    </title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    {{-- <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet"
    /> --}}
    <link rel="stylesheet" href="assets/css/laporan.css" />
    <link href="assets/images/favicon-pdf.ico">
  </head>
  <style>
    body {
  font-family: sans-serif;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  text-align: left;
  padding: 8px;
  border: 1px solid #ddd;
}

th {
  background-color: #f2f2f2;
}

.total-row {
  font-weight: bold;
}

.header {
  text-align: center;
}

.header-title {
  font-size: 24px;
  font-weight: bold;
}

.header-subtitle {
  font-size: 16px;
}

.header-date {
  font-size: 12px;
}

.income {
  background-color: #e0ffe0;
}

.expense {
  background-color: #ffe0e0;
}
    </style>
  <body>
    <section class="wrapper-invoice">
      <!-- switch mode rtl by adding class rtl on invoice class -->
      <div class="invoice">
        <div class="invoice-information">
          {{-- <p><b>Invoice #</b> : 12345</p> --}}
          {{-- <p><b>Created Date </b>: May, 07 2022</p> --}}
          <br>
          <br>
          <br>
          <p><b>Dicetak pada</b> : {{ date('F, d Y H:i:s', strtotime(now())) }}</p>
        </div>
        <!-- logo brand invoice -->
        <div class="invoice-logo-brand">
          <!-- <h2>Tampsh.</h2> -->
          <img src="assets/images/kas.jpg" alt="">
        </div>
        <!-- invoice head -->
        <div class="invoice-head">
          <div class="head client-info">
            <br>
            <br>
            <br>
            <p><b>Laporan Kas: </b> {{$nama_akun}} </p>
            {{-- <p>NPWP: 12.345.678.910.111213.1415</p>
            <p>Bondowoso, Jawa timur</p>
            <p>Jln. Rengganis 05, Bondowoso</p> --}}
          </div>
          <div class="head client-data">
            <p>-</p>
            {{-- <p>Mohammad Sahrullah</p>
            <p>Bondowoso, Jawa timur</p>
            <p>Jln. Duko Kembang, Bondowoso</p> --}}
          </div>
        </div>
        <!-- invoice body-->
        <div class="invoice-body">
            <table>
                <tr>
                    <th style="background-color:#ced5d2">Saldo Awal</th>
                    <td style="background-color:#fff">Rp. {{ number_format($saldoAwal, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="background-color:#40cf7e">Semua Pemasukan (+)</th>
                    <td style="background-color:#fff">Rp. {{ number_format($totalPemasukan, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="background-color:#da4747">Semua Pengeluaran (-)</th>
                    <td style="background-color:#fff">Rp. {{ number_format($totalPengeluaran, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <th style="text-align: right; background-color: #fff">Akumulasi</th>
                    <td style="background-color:#fff">Rp. {{ number_format($totalPemasukan + $totalPengeluaran, 2, ',', '.') }}</td>                </tr>
                <tr>
                    <th style="background-color:#ced5d2">Saldo Akhir</th>
                    <td style="background-color:#fff">Rp. {{ number_format($saldoAwal + $totalPemasukan - abs($totalPengeluaran), 2, ',', '.') }}</td>                </tr>
            </table>
        
            <br><br>
        
            <table>
                <tr>
                    <th style="background-color:#40cf7e">Pemasukan</th>
                    <th style="background-color:#40cf7e; text-align:right;"></th>
                </tr>
                @php
                    $totalPemasukanKategori = 0;
                @endphp
                @foreach ($groupedKategori as $kategori => $items)
                    @if ($items->first()->subcategories_id == 1)
                        <tr>
                            <td style="background-color:#fff">{{ $kategori }}</td>
                            <td style="background-color:#fff">Rp. {{ number_format($items->sum('jumlah'), 2, ',', '.') }}</td>
                        </tr>
                        @php
                            $totalPemasukanKategori += $items->sum('jumlah');
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <th style="background-color:#fff; text-align:right;">Total Pemasukan</th>
                    <td style="background-color:#fff">Rp. {{ number_format($totalPemasukanKategori, 2, ',', '.') }}</td>
                </tr>
            </table>
        
            <br><br>
        
            <table>
                <tr>
                    <th style="background-color:#da4747">Pengeluaran</th>
                    <th style="background-color:#da4747; text-align:right;"></th>
                </tr>
                @php
                    $totalPengeluaranKategori = 0;
                @endphp
                @foreach ($groupedKategori as $kategori => $items)
                    @if ($items->first()->subcategories_id == 2)
                        <tr>
                            <td style="background-color:#fff">{{ $kategori }}</td>
                            <td style="background-color:#fff">Rp. {{ number_format($items->sum('jumlah'), 2, ',', '.') }}</td>
                        </tr>
                        @php
                            $totalPengeluaranKategori += $items->sum('jumlah');
                        @endphp
                    @endif
                @endforeach
                <tr>
                    <th style="background-color:#fff; text-align:right;">Total Pengeluaran</th>
                    <td style="background-color:#fff">Rp. {{ number_format($totalPengeluaranKategori, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <!-- invoice footer -->
        <div class="invoice-footer">
        </div>
      </div>
    </section>
  </body>
</html>
