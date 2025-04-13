<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
{
    protected $groupedKasBank;
    protected $nama_akun;
    protected $totalPemasukan;
    protected $totalPengeluaran;
    protected $saldo;
    protected $saldoAwal;
    protected $saldoAkhir;
    protected $kas_bank;

    public function __construct($groupedKasBank, $kas_bank, $nama_akun, $totalPemasukan, $totalPengeluaran, $saldo, $saldoAwal, $saldoAkhir)
    {
        $this->groupedKasBank = $groupedKasBank;
        $this->nama_akun = $nama_akun;
        $this->totalPemasukan = $totalPemasukan;
        $this->totalPengeluaran = $totalPengeluaran;
        $this->saldo = $saldo;
        $this->saldoAwal = $saldoAwal;
        $this->saldoAkhir = $saldoAkhir;
        $this->kas_bank = $kas_bank;
    }

    public function collection()
    {
        $data = new Collection();

        foreach ($this->kas_bank as $item) {
            $data->push([
                'Tanggal Bukti' => $item->tanggal_bukti,
                'Nama Akun' => $item->nama_akun,
                'Kategori' => $item->kategori,
                'Jumlah' => $item->jumlah,
                'Keterangan' => $item->keterangan,
                'Nama User' => $item->nama_user,
                'Tanggal Log' => $item->tanggal_log,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Tanggal Bukti',
            'Nama Akun',
            'Kategori',
            'Jumlah',
            'Keterangan',
            'Nama User',
            'Tanggal Log',
        ];
    }

    public function map($row): array
    {
        return [
            $row['Tanggal Bukti'],
            $row['Nama Akun'],
            $row['Kategori'],
            $row['Jumlah'],
            $row['Keterangan'],
            $row['Nama User'],
            $row['Tanggal Log'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling untuk header (nama kolom)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);

        // Definisi border untuk semua sel
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        // Terapkan style ke seluruh sel
        $sheet->getStyle('A1:G' . ($sheet->getHighestRow()))->applyFromArray($styleArray);

        return [
            1 => $styleArray,
        ];
    }
}
