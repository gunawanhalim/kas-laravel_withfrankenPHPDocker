<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Report Daily CV.</title>
</head>
<style>
    /* General Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Container */
    body {
        font-family: Arial, sans-serif;
        color: #333;
        padding: 20px;
        margin-left: 15em;
        margin-right: 15em;
    }

    /* Header with logo and date */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header .logo {
        display: flex;
        align-items: center;
    }

    .header .logo img {
        width: 50px;
        height: 50px;
        margin-right: 10px;
    }

    .header .title {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .header .date {
        text-align: right;
        font-size: 14px;
        color: #666;
    }

    /* Summary Totals */
    .summary {
        font-size: 14px;
        text-align: left;
        margin-top: 10px;
        margin-bottom: 15px;
        background-color: #eee;
        font-weight: bold;
        padding: 10px;

    }

    .summary .label {
        padding: 5px;
        background-color: #eee;
        font-weight: bold;
        width: 150px;
        display: inline-block;
    }

    .summary .value {
        float: right;
    }

    /* Income & Expense Totals */
    .income,
    .expense {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
        text-align: left;
        margin-bottom: 12px;
    }

    .income {
        background-color: #d1f0d1;
        color: #333;
    }

    .expense {
        background-color: #f8d8d8;
        color: #333;
    }

    /* Accumulation and Final Balance */
    .total,
    .final-balance {
        font-size: 14px;
        text-align: left;
        padding: 10px 0;
        margin-top: 10px;
        color: #333;
    }

    .final-balance {
        background-color: #eee;
        text-align: left;
        padding: 5px;
        border-radius: 5px;
        font-weight: bold;
    }

    /* Section Titles */
    .section-title {
        font-size: 16px;
        padding: 8px;
        margin-top: 15px;
        font-weight: bold;
        color: white;
        text-align: left;
    }

    .expense-section-title {
        background-color: #f8b2b2;
    }

    .income-section-title {
        background-color: #c7f5c7;
    }

    /* Expense and Income Details */
    .details {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
    }

    .details th,
    .details td {
        padding: 8px 10px;
        text-align: left;
    }

    .details th {
        font-weight: bold;
        border-bottom: 1px solid #ddd;
    }

    .details td.amount {
        text-align: right;
    }

    /* Total Row */
    .details .total-row td {
        font-weight: bold;
        border-top: 2px solid #ddd;
    }
</style>

<body>

    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Logo"> <!-- Replace 'logo.png' with your logo image source -->
            <div>
                <div class="title">Test</div>
                <div>Laporan Harian: Umum</div>
            </div>
        </div>
        <div class="date">
            30 Oktober 2024<br>
            Dicetak 31 Okt 2024, 09:58
        </div>
    </header>

    <section class="summary">
        <div class="label">Saldo Awal Hari</div>
        <div class="value">Rp. 16.299.900,00</div>
    </section>

    <section class="income">
        Semua Pemasukan (+) Rp. 1.234.567.890,00
    </section>

    <section class="expense">
        Semua Pengeluaran (-) Rp. 1.234.567.890,15
    </section>

    <div class="total">
        Akumulasi: Rp. -0,15
    </div>

    <section class="summary">
        <div class="label">Saldo Akhir Hari</div>
        <div class="value">Rp. 16.299.899,85</div>
    </section>

    <section class="section">
        <div class="section-title expense-section-title">PENGELUARAN</div>
        <table class="details">
            <tr>
                <th>Keterangan</th>
                <th class="amount">Rp.</th>
            </tr>
            <tr>
                <td>Perawatan Motor</td>
                <td class="amount">1.234.567.890,15</td>
            </tr>
            <tr class="total-row">
                <td>Total</td>
                <td class="amount">1.234.567.890,15</td>
            </tr>
        </table>
    </section>

    <!-- Repeat for income section if needed -->

</body>

</html>
