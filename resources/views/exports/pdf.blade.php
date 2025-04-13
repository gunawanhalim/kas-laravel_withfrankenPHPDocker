<!DOCTYPE html>
<html>

<head>
    <title>PDF Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <h1>Data Kas Bank</h1>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Akun</th>
                <th>Kategori</th>
                <th>Nama Kategori</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kas_bank as $data)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_bukti)->format('Y-m-d H:i:s') }}</td>
                    <td>{{ $data->nama_akun }}</td>
                    @if ($data->subcategories_id == '1')
                        <td>
                            Pemasukan
                        </td>
                    @elseif ($data->subcategories_id == '2')
                        <td>
                            Pengeluaran
                        </td>
                    @endif
                    <td>{{ $data->kategori }}</td>
                    <td>{{ number_format($data->total_jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h1>Data Piutang</h1>
    <table>
        <thead>
            <tr>
                <th>Tanggal Bukti</th>
                <th>Nomor Bukti</th>
                <th>Nomor Nota</th>
                <th>Kategori</th>
                <th>Nama Pelanggan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($piutang as $data)
                <tr>
                    <td>{{ $data->tanggal_bukti }}</td>
                    <td>{{ $data->nomor_bukti }}</td>
                    <td>{{ $data->nomor_nota }}</td>
                    @if ($data->kategori == '1')
                        <td>
                            Pemasukan
                        </td>
                    @elseif ($data->kategori == '2')
                        <td>
                            Pengeluaran
                        </td>
                    @endif
                    <td>{{ $data->nama_pelanggan }}</td>
                    <td>Rp. {{ number_format($data->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h1>Data Penjualan</h1>
    <table>
        <thead>
            <tr>
                <th>Tanggal Nota</th>
                <th>Nomor Nota</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Nama Sales</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan as $data)
                <tr>
                    <td>{{ $data->tanggal_nota }}</td>
                    <td>{{ $data->nomor_nota }}</td>
                    <td>{{ $data->nama_pelanggan }}</td>
                    <td>{{ $data->alamat }}</td>
                    <td>{{ $data->nama_sales }}</td>
                    <td>{{ number_format($data->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}
    </div>
</body>

</html>
