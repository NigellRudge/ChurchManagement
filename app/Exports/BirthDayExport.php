<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class BirthDayExport extends BaseExport implements FromCollection, WithColumnFormatting, WithCustomStartCell, WithEvents
{
    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->startRow = 6;
        $this->headingRow = 'A5:D5';
        $this->headingRowIndex = 5;
        $this->contentColumns = ['A','B','C','D'];
    }

    public function collection()
    {
        if(app()->getLocale() == 'nl'){
            setlocale(LC_TIME,'Dutch');
        }
        $items = DB::table('member_info')
                ->whereBetween(DB::raw("DATE_ADD(birth_date,interval FLOOR(DATEDIFF(DATE_ADD(curdate(),interval 7 day ),birth_date) / 365.25) year)"),[$this->data['start_date'],$this->data['end_date']])
                ->select('name',DB::raw("DATE_ADD(birth_date,interval FLOOR(DATEDIFF(DATE_ADD(curdate(),interval 7 day ),birth_date) / 365.25) year) as 'b_day'"),DB::raw("FLOOR(DATEDIFF(DATE_ADD(curdate(),interval 7 day ),birth_date) / 365.25) AS age_new"),'phone_number')
                ->orderBy('b_day','asc');
        $this->itemCount = $items->count();
        $items = $items->get();
        foreach ($items as $item){
            $item->b_day = ucfirst(Carbon::parse($item->b_day)->formatLocalized('%A %d %B %Y'));
        }
        return $items;
    }

    public function columnFormats(): array
    {
        return [
            
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class =>[$this,'createHeading'],
//            AfterSheet::class => [$this,'setData']
        ];
    }

    /**
     * @param BeforeSheet $event
     * @return BeforeSheet
     * @throws Exception
     */
    public function createHeading(BeforeSheet $event){

        $this->setInfoValue($event,'A1','B1',trans('common.title_label'),$this->title);
        $this->setInfoValue($event,'A2','B2',trans('common.start_date'),$this->data['start_date']);
        $this->setInfoValue($event,'A3','B3',trans('common.end_date'),$this->data['end_date']);
        $this->setInfoValue($event,'A4','B4','week',$this->data['week']);

        for($i=1;$i < 5;$i++){
            $event->sheet->getDelegate()->getStyle($i)->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
            $event->sheet->getDelegate()->getStyle($i)->getActiveSheet()->getStyle($i)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        }

//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setColor(new Color(Color::COLOR_WHITE));
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getRowDimension($this->headingRowIndex)->setRowHeight(20);

        foreach ($this->contentColumns as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension("$column")->setAutoSize(true);
        }
//
//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
//        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFill()->setFillType(Fill::FILL_SOLID)
//            ->getStartColor()->setARGB('018c1d');
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.date_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.age_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.phone_number_label'));
        return $event;
    }
}
