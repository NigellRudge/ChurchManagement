<?php

namespace App\Exports;

use App\Models\ServiceClubMemberInfo;
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

class ServiceClubExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{

    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->headingRow = 'A1:G1';
        $this->contentColumns = ['A','B','C','D','E','F','G'];
    }

    public function query()
    {
       $query = ServiceClubMemberInfo::select('id','id_number','name','gender','phone_number','profession','join_date')->orderBy('name','desc');
       if(isset($this->data['gender_id'])){
           $query->where('gender_id','=',$this->data['gender_id']);
       }
        $this->itemCount = $query->count();
       return $query;
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_DMYMINUS
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class =>[$this,'createHeading'],
        ];
    }

    public function createHeading(BeforeSheet $event){

        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);

        foreach ($this->contentColumns as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }

        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue('Id');
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.id_number_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.gender_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.phone_number_label'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.profession_label'));
        $event->sheet->getDelegate()->getCell("G$this->headingRowIndex")->setValue(trans('common.join_date_label'));
        return $event;
    }
}
