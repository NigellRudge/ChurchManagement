<?php


namespace App\Exports;


use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BaseExport implements  WithTitle, WithCustomStartCell
{

    protected $title;
    protected $startRow = 2;
    protected $headingRowIndex = 1;
    protected $data = array();
    protected $itemCount = 0;
    protected $headingRow = 'A1:F1';
    protected $contentColumns = ['A','B','C','D','E','F'];

    public function __construct($title, array $data)
    {
        $this->title = $title;
        $this->data = $data;
    }


    public function startCell(): string
    {
        return "A$this->startRow";
    }


    public function title(): string
    {
        return $this->title;
    }

    public function setData(AfterSheet $event){

        $event->sheet->getDelegate()->getStyle($this->headingRow)->applyFromArray([
            'borders' =>[
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [ 'argb' => Color::COLOR_BLACK]
                ]
            ]
        ]);

        $endCount = $this->headingRowIndex + $this->itemCount;
        foreach ($this->contentColumns as $column){
            $range = "$column$this->headingRowIndex:$column$endCount";
            $event->sheet->getDelegate()->getStyle($range)->applyFromArray([
                'borders' =>[
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [ 'argb' => Color::COLOR_BLACK]
                    ]
                ]
            ]);
            $event->sheet->getDelegate()->getStyle($range)->getActiveSheet()->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $event->sheet->getDelegate()->getStyle($range)->getActiveSheet()->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }
        for($count = 0; $count <= $this->itemCount; $count++){
            $row = $this->startRow + $count;
            $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(25);
        }
    }


    protected function setInfoValue($event,$labelCell,$valueCell,$labelValue,$contentValue){
        $event->sheet->getDelegate()->getCell($labelCell)->setValue($labelValue);
        $event->sheet->getDelegate()->getCell($valueCell)->setValue($contentValue);
        return $event;
    }

}
