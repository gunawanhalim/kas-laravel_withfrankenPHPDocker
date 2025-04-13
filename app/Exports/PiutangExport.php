<?php
// php artisan make:export PiutangExport --model=piutang
namespace App\Exports;

use App\Models\piutang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;

class PiutangExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Fetch specific fields from KasBank model
        $PiutangData = piutang::all();

        // Transform the data if needed
        $transformedData = $PiutangData->map(function ($item, $key) {
            // Handle null values for nomor_bukti and nomor_nota
        
            // Determine the kategori
            $kategori = $item->kategori == '1' ? 'Pemasukan' : ($item->kategori == '2' ? 'Pengeluaran' : '');
            $jumlah = number_format($item->jumlah, 0, ',', '.'); // Assuming you want to format it as an integer
            return [
                $item->tanggal_bukti,
                $item->nomor_bukti,
                $item->nomor_nota,
                $item->nama_akun,
                $item->nama_pelanggan,
                $kategori,
                $jumlah,
            ];
        });
        return $transformedData;
    }

    public function headings(): array
    {
        return ['Tanggal Bukti',
                'Nama Akun',
                'Kategori',
                'Nama Kategori',
                'Keterangan', 
                'Jumlah'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Get the highest row and column index
                $lastRow = $event->sheet->getHighestRow();
                $lastColumn = $event->sheet->getHighestColumn();

                // Apply borders to the entire range of cells
                $event->sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'], // Black border color
                        ],
                    ],
                ]);

                // Apply bold font and center alignment to the header row
                $event->sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FFA500', // Orange color code
                        ],
                    ],
                ]);
            },
        ];
    }

    public function title(): string
    {   
        
        return "Piutang";
    }
}
