<?php
// php artisan make:export PiutangExport --model=piutang
namespace App\Exports;

use App\Models\penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class PenjualanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Fetch specific fields from KasBank model
        $PenjualanData = penjualan::all();

        // Transform the data if needed
        $transformedData = $PenjualanData->map(function ($item, $key) {
            // Handle null values for nomor_bukti and nomor_nota
        
            // Determine the kategori
            $jumlah = number_format($item->total, 0, ',', '.'); // Assuming you want to format it as an integer
            return [
                $item->tanggal_nota,
                $item->nomor_nota,
                $item->nama_pelanggan,
                $item->alamat,
                $item->nama_sales,
                $item->nama_user,
                $item->tanggal_log,
                $jumlah,
            ];
        });
        return $transformedData;
    }

    public function headings(): array
    {
        return 
        [
           'Tanggal Nota',
           'Nomor Nota',
           'Nama Pelanggan',
           'Alamat',
           'Nama Sales', 
           'Nama User Menginput', 
           'Tanggal Log User Menginput', 
           'Jumlah'
        ];
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
        
        return "Penjualan";
    }
}
