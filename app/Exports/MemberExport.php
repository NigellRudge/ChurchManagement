<?php

namespace App\Exports;

use App\Models\MemberInfo;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class MemberExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{

    function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->headingRow = 'A6:J6';
        $this->headingRowIndex = 6;
        $this->contentColumns = ['A','B','C','D','E','F','G','H','I','J'];
        $this->startRow = 6;
    }


    public function query()
    {
//        dd($this->data);
        $items = MemberInfo::select('name','birth_date','age','gender','phone_number','email','address','member_type','status','baptized');
        if(isset($this->data['baptized'])){
            $items->where('baptized','=',$this->data['baptized']);
        }
        if(isset($this->data['member_type_id'])){
            $items->where('member_type_id','=',$this->data['member_type_id']);
        }
        if(isset($this->data['gender_id'])){
            $items->where('gender_id','=',intval($this->data['gender_id']));
        }
        if(isset($this->data['to_age'])){
            $items->where('age','<=',$this->data['to_age']);
        }
        if(isset($this->data['from_age'])){
            $items->where('age','>=',$this->data['from_age']);
        }
        if(isset($this->data['status']) &&  $this->data['status'] != 3){
            $items->where('active','=',$this->data['status']);
        }
        $items->orderBy('name');
        $this->itemCount = $items->count();
        return $items;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class =>[$this,'createHeading'],
        ];
    }

    public function createHeading(BeforeSheet $event){

        $this->setInfoValue($event,'A1','B1',trans('common.title_label'),$this->title);
        $this->setInfoValue($event,'A2','B2',trans('common.gender_label'),isset($this->data['gender_id']) ? $this->data['gender_id']: trans('common.all'));
        $this->setInfoValue($event,'A3','B3',trans('common.member_type_label'),isset($this->data['member_type_id']) ? $this->data['member_type_id']: trans('common.all'));
        $this->setInfoValue($event,'A4','B4',trans('common.baptized_label'), isset($data['baptized']) ? $data['baptized']: trans('common.all_label'));

        $event->sheet->getDelegate()->getRowDimension($this->headingRowIndex)->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        foreach ($this->contentColumns as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension("$column")->setAutoSize(true);
        }
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.birth_date_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.age_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.gender_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.phone_number_label'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.email_label'));
        $event->sheet->getDelegate()->getCell("G$this->headingRowIndex")->setValue(trans('common.address_label'));
        $event->sheet->getDelegate()->getCell("H$this->headingRowIndex")->setValue(trans('common.member_type_label'));
        $event->sheet->getDelegate()->getCell("I$this->headingRowIndex")->setValue(trans('common.status_label'));
        $event->sheet->getDelegate()->getCell("J$this->headingRowIndex")->setValue(trans('common.baptized_label'));
        return $event;
    }

    public function setData(AfterSheet $event)
    {
//        for($count = 0; $count <= $this->itemCount +1; $count++){
//            $row = $this->startRow + $count;
//            $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(20);
//        }
//        $sumRow = $this->startRow + $this->itemCount - 1;
//        $event->sheet->getDelegate()->getStyle("E$sumRow")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
//        $this->setInfoValue($event,"D$sumRow","E$sumRow",trans('common.total_amount_label'),$this->sumAmount);
    }
}
