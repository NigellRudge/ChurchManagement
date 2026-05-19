<?php

namespace App\Exports;

use Illuminate\Database\Query\Builder;
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

class InfantDedicationExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents
{

    public function __construct($title, array $data)
    {
        parent::__construct($title,$data);
    }


    public function query()
    {
        $query = DB::table('infant_dedication_info')
                    ->whereDate('dedication_date','>',$this->data['start_date'])
                    ->whereDate('dedication_date','<',$this->data['end_date']);
        $this->itemCount = $query->count();
        return $query->select('id', 'name', 'mother', 'father', 'birth_date', 'dedication_date')->orderByDesc('dedication_date');

    }


    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => [$this,'createHeading'],
            AfterSheet::class => [$this,'setData']
        ];
    }

    public function title(): string
    {
        return $this->title;
    }

    public function createHeading(BeforeSheet $event)
    {
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setColor(new Color(Color::COLOR_WHITE));
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getRowDimension($this->headingRowIndex)->setRowHeight(30);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getStyle($this->headingRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('018c1d');
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue('Id');
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.mother_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.father_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.birth_date_label'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.dedication_date_label'));

        $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);
        $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(true);
    }

}
