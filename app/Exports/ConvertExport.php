<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet;

class ConvertExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents
{

    public function __construct($title,$data)
    {
        parent::__construct($title, $data);
         $this->headingRow = 'A1:F1';
         $this->contentColumns = ['A','B','C','D','E','F'];
    }
    public function query()
    {
        $query = DB::table('converts_info')
                    ->select('id','name','gender','convert_date','phone_number','address')
                    ->orderBy('name','desc');
        $this->itemCount = $query->count();

        if(isset($this->data['from_date'])){
            $query->where('convert_date','>=',$this->data['from_date']);
        }
        if(isset($this->data['to_date'])){
            $query->where('convert_date','<=',$this->data['to_date']);
        }
        if(isset($this->data['gender'])){
            $query->where('gender_id',$this->data['gender_id']);
        }
        return $query;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class =>[$this,'createHeading'],
            AfterSheet::class => [$this,'setData']
        ];

    }


    public function title(): string
    {
        return $this->title;
    }



    public function createHeading(BeforeSheet $event){

        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setColor(new Color(Color::COLOR_WHITE));
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getRowDimension($this->headingRowIndex)->setRowHeight(30);

        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension('A')->setWidth(5.0);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension('B')->setWidth(22);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension('C')->setWidth(22);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension('D')->setWidth(22);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension('E')->setWidth(22);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension('F')->setWidth(22);

        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('018c1d');
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue('Id');
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.gender_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.convert_date_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.phone_number_label'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.address_label'));
        return $event;
    }

}
