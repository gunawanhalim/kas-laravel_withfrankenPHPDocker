<?php

namespace App\Exports;

use App\Models\kas_bank;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;



class KasBankExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithTitle, WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $startDate;
    protected $endDate;
    protected $search;

    public function __construct($startDate = null, $endDate = null, $searchData = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $searchData;
    }

    public function collection()
    {
        // Query data berdasarkan filter
        $query = kas_bank::query();

        if ($this->startDate) {
            $query->whereDate('tanggal_bukti', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('tanggal_bukti', '<=', $this->endDate);
        }

        if ($this->search) {
            $query->where('nama_akun', 'like', '%' . $this->search . '%');
        }

        // Ambil data yang sudah difilter
        $kasBankData = $query->get();

        // Transformasi data jika perlu
        $transformedData = $kasBankData->map(function ($item) {
            // Ubah format atau lakukan transformasi data lainnya jika diperlukan
            return [
                $item->tanggal_bukti,
                $item->nama_akun,
                $item->kategori,
                $item->subcategories_id,
                $item->keterangan,
                $item->jumlah,
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
        
        return "Kas Bank";
    }

    public function sheets(): array
    {
        return [
            $this,
            new PiutangExport(),
            new PenjualanExport(),
        ];
    }

}
