<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RegistrationSheetExport implements FromQuery,WithEvents,WithTitle, WithCustomStartCell
{
    private $sheetId;
    private $startRow = 6;
    private $headingRowIndex = 5;
    private $data = array();
    private $itemCount = 0;
    private $headingRow = 'A5:E5';

    public function __construct($id,$data)
    {
        $this->sheetId = $id;
        $this->data = $data;
    }

    public function query()
    {
        $this->itemCount = DB::table('registration_sheet_item_info')
            ->where('sheet_id',$this->sheetId)->count() + 1;
        return DB::table('registration_sheet_item_info')
                ->select('id','member','phone_number','paid_amount','registration_date')
                ->where('sheet_id',$this->sheetId)
                ->orderBy('id');
    }

    public function registerEvents(): array
    {
        return [

            BeforeSheet::class =>[$this,'setInfo'],

            AfterSheet::class => [$this,'setData']
        ];
    }


    public function title(): string
    {
        return 'Registered Members';
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return "A$this->startRow";
    }

    private function setInfoValue($event,$labelCell,$valueCell,$labelValue,$contentValue){
        $event->sheet->getDelegate()->getCell($labelCell)->setValue($labelValue);
        $event->sheet->getDelegate()->getCell($valueCell)->setValue($contentValue);
        return $event;
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
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue('Phone Number');
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue('Amount paid');
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue('Register Date');
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
        $contentColumns = ['A','B','C','D','E'];
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
            $event->getDelegate()
                ->getStyle("D$row")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        }

    }

    public function setInfo(BeforeSheet $event){
        $event = $this->createHeading($event);

        // Sheet Name
        $event = $this->setInfoValue($event,'B1','C1','Sheet Name:',$this->data['sheet_name']);
        //Generated Date
        $event = $this->setInfoValue($event,'B2','C2','Generated Date:',$this->data['generated_date']);
        //Date
        $event = $this->setInfoValue($event,'D1','E1','Last Registration Date:',$this->data['last_date']);
        //Generated By
        $event = $this->setInfoValue($event,'D2','E2','Generated By:',$this->data['generated_by']);
        // Currency
        $event = $this->setInfoValue($event,'D3','E3','Currency:',$this->data['currency']);
        // Total amount
        $event = $this->setInfoValue($event,'B3','C3','Total Amount received',$this->data['total_amount']);

        $event->getDelegate()
            ->getStyle("C3")
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $event->sheet->getDelegate()->getStyle('B1:B3')->getFont()->setSize(11);
        $event->sheet->getDelegate()->getStyle('B1:B3')->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle('B1:B2')->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle('B1:B2')->getActiveSheet()->getRowDimension('2')->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle('B1:B2')->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle('D1:D3')->getFont()->setSize(11);
        $event->sheet->getDelegate()->getStyle('D1:D3')->getFont()->setBold(true);

        $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);

    }
}
