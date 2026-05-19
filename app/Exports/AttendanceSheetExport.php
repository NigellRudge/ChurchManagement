<?php

namespace App\Exports;

use App\Models\RollCall;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Style\Color;


class AttendanceSheetExport implements  WithMultipleSheets
{
    private $sheet_id;
    private $data = array();
    public function __construct($id,$data)
    {
        $this->sheet_id = $id;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function sheets():array
    {
        return  [
            new PresentSheet('Aanwezig',$this->sheet_id,$this->data),
            new NotPresentSheet('Niet Aanwezig',$this->sheet_id,$this->data),
        ];
    }

    public function headings(): array
    {
        return [
            'id',
            'Naam',
            'Eagle Group'
        ];
    }

    public function styles(Worksheet $worksheet){
        return [

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
            $cellRange = 'A1:C1';
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(15);
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setColor(new Color(Color::COLOR_WHITE));
            $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
            $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getStyle($cellRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getStyle($cellRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            //$event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setStartColor(new Color(Color::COLOR_BLUE));
            //$event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setEndColor(new Color(Color::COLOR_BLUE));
            $event->sheet->getDelegate()->getStyle($cellRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('018c1d');
            $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray([
                'borders' =>[
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [ 'argb' => Color::COLOR_BLACK]
                    ]
                ]
            ]);
            }


        ];
    }
}
