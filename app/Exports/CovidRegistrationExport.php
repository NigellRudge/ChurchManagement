<?php

namespace App\Exports;

use App\Models\CovidRegistrationSheetItemInfo;
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
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CovidRegistrationExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents
{

    private $sheetId;
    public function __construct($title, $sheetId,array $data=[])
    {
        parent::__construct($title, $data);
        $this->sheetId = $sheetId;
        $this->contentColumns = ['A','B','C','D','E'];
        $this->headingRow = 'A1:E1';
    }


    public function query()
    {
        $items = CovidRegistrationSheetItemInfo::where('sheet_id','=',$this->sheetId)
            ->select('member_id','member','gender','phone_number','id_number')
            ->orderByDesc('id');
        $this->itemCount = $items->count();
        return $items;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class =>[$this,'createHeading'],
        ];
    }

    public function createHeading(BeforeSheet $event){
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getRowDimension($this->headingRowIndex)->setRowHeight(20);

        foreach ($this->contentColumns as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }

        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue('Id');
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.gender_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.phone_number_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.id_number_label'));
        return $event;
    }


}
