<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class EagleOverviewExport implements FromQuery,WithEvents,WithTitle, WithCustomStartCell
{

    private $startRow = 6;
    private $headingRowIndex = 5;
    private $data = array();
    private $itemCount = 0;
    private $headingRow = 'A5:D5';

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return Builder|void
     */
    public function query()
    {
        $this->itemCount = DB::table('eagle_group_info')->count() + 1;
        return DB::table('eagle_group_info')
                ->select('id','name','team_captain','num_members')
                ->orderBy('name','desc');
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return "A$this->startRow";
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            BeforeSheet::class =>[$this,'setInfo'],

            AfterSheet::class => [$this,'setData']
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Eagle groups';
    }

    private function createHeading(BeforeSheet $event){


        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setColor(new Color(Color::COLOR_WHITE));
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getRowDimension($this->headingRowIndex)->setRowHeight(30);
//        $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getColumnDimension('A')->setWidth(5);
//        $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getColumnDimension('B')->setWidth(22);
//        $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getColumnDimension('C')->setWidth(22);
//        $event->sheet->getDelegate()->getStyle($cellRange)->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('018c1d');
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue('Id');
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue('Name');
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue('Team Captain');
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue('Number of Members');
        return $event;
    }

    private function setInfoValue($event,$labelCell,$valueCell,$labelValue,$contentValue){
        $event->sheet->getDelegate()->getCell($labelCell)->setValue($labelValue);
        $event->sheet->getDelegate()->getCell($valueCell)->setValue($contentValue);
        return $event;
    }

    public function setData(AfterSheet $event){
        $event->sheet->getDelegate()->getStyle("A$this->headingRowIndex:D$this->headingRowIndex")->applyFromArray([
            'borders' =>[
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => [ 'argb' => Color::COLOR_BLACK]
                ]
            ]
        ]);
        $contentColumns = ['A','B','C','D'];
        $endCount = $this->headingRowIndex + $this->itemCount;
        foreach ($contentColumns as $column){
            $range = "$column$this->headingRowIndex:$column$endCount";
            $event->sheet->getDelegate()->getStyle($range)->applyFromArray([
                'borders' =>[
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => [ 'argb' => Color::COLOR_BLACK]
                    ]
                ]
            ]);
        }
        for($count = 0; $count <= $this->itemCount; $count++){
            $row = $this->startRow + $count;
            $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(20);
        }
    }

    public function setInfo(BeforeSheet $event){
        $event = $this->createHeading($event);

        // Sheet Name
        $event = $this->setInfoValue($event,'B1','C1','Name:',$this->data['name']);
        //Generated Date
        $event = $this->setInfoValue($event,'B2','C2','Generated Date:',$this->data['generated_date']);
        //Date
        $event = $this->setInfoValue($event,'D1','E1','Number of groups',$this->data['num_groups']);
        //Generated By
        $event = $this->setInfoValue($event,'D2','E2','Generated By:',$this->data['generated_by']);

        $event->sheet->getDelegate()->getStyle('B1:B3')->getFont()->setSize(11);
        $event->sheet->getDelegate()->getStyle('B1:B3')->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle('B1:B2')->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle('B1:B2')->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle('B1:B2')->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle('D1:D2')->getFont()->setSize(11);
        $event->sheet->getDelegate()->getStyle('D1:D2')->getFont()->setBold(true);

        $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);

    }
}
