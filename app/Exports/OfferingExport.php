<?php

namespace App\Exports;

use App\Models\OfferingInfo;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OfferingExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{
    private $sumAmount = 0;
    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->startRow = 6;
        $this->contentColumns = [ 'A' ,'B' ,'C', 'D','E','F','G',];
        $this->headingRowIndex = 5;
        $this->headingRow = 'A5:G5';
    }

    public function query()
    {
        $items = DB::table('offering_info')->select('name','date', DB::raw("(srd_amount + 0.00)/100"),DB::raw("(usd_amount + 0.00)/100"), DB::raw("(euro_amount + 0.00)/100"),DB::raw("((total_amount + 0.00)/100) as 'total_amount'") ,'counted_by')->orderBy('date');
        if(isset($this->data['from_date'])){
            $items->where('date','>',$this->data['from_date']);
        }
        if(isset($this->data['to_date'])){
            $items->where('date','<',$this->data['to_date']);
        }
        $this->itemCount = $items->count() + 1;
        $this->sumAmount = $items->sum('total_amount') /100;
        return $items;
    }

    public function createHeading(BeforeSheet $event){

        $this->setInfoValue($event,'A1','B1',trans('common.title_label'),$this->title);
        $this->setInfoValue($event,'A2','B2',trans('common.from_label'),isset($this->data['from_date']) ? $this->data['from_date']: trans('common.no_date_selected'));
        $this->setInfoValue($event,'A3','B3',trans('common.to_date'),isset($this->data['to_date']) ? $this->data['to_date']: trans('common.no_date_selected'));

        for($i=1;$i < 4;$i++){
            //$event->sheet->getDelegate()->getStyle($i)->getActiveSheet()->getRowDimension($i)->setRowHeight(25);
            $event->sheet->getDelegate()->getStyle($i)->getActiveSheet()->getStyle($i)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setColor(new Color(Color::COLOR_WHITE));
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getRowDimension($this->headingRowIndex)->setRowHeight(20);

        foreach ($this->contentColumns as $column){
//            if($column == 'A'){
//                $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension($column)->setWidth(15.0);
//                continue;
//            }
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
//            $event->sheet->getDelegate()->getStyle($column)
//                ->getAlignment()
//                ->setWrapText(true);
        }

//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFill()->setFillType(Fill::FILL_SOLID)
//            ->getStartColor()->setARGB('018c1d');
//        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue('Id');
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.date_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.srd_amount'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.usd_amount'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.eur_amount'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.total_amount_label'));
        $event->sheet->getDelegate()->getCell("G$this->headingRowIndex")->setValue(trans('common.counted_by_label'));
        return $event;
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class =>[$this,'createHeading'],
            AfterSheet::class => [$this,'setData']
        ];
    }

    public function setData(AfterSheet $event){
        for($count = 0; $count <= $this->itemCount +1; $count++){
            $row = $this->startRow + $count;
            $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(20);
        }
        $sumRow = $this->startRow + $this->itemCount;
        $event->sheet->getDelegate()->getStyle("F$sumRow")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->setInfoValue($event,"E$sumRow","F$sumRow",trans('common.total_amount_label'),$this->sumAmount);
    }
}
