<?php

namespace App\Exports;

use App\Models\BudgetItemInfo;
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

class BudgetExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{

    private $query;
    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->startRow = 7;
        $this->headingRowIndex = 6;
        $this->headingRow = 'A6:H6';
        $this->contentColumns = ['A','B','C','D','E','F','G','H'];
    }

    public function query()
    {
        $items = DB::table('budget_item_info')->where('budget_id','=',$this->data['budget_id'])->select(/*'id',*/'name','description','currency',DB::raw('amount/100'),DB::raw('amount_in_base_currency/100'),'created_at','creator')->orderBy('amount');
        $this->itemCount = $items->count() + 1;
        $this->query = $items;
        return $items;
    }

    public function columnFormats(): array
    {
        return [
            'D'=> NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'C' => NumberFormat::FORMAT_DATE_DMYMINUS,
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class =>[$this,'createHeading'],
            AfterSheet::class => [$this,'setData']
        ];
    }

    public function createHeading(BeforeSheet $event){

        $this->setInfoValue($event,'A1','B1',trans('common.title_label'),$this->title);
        $this->setInfoValue($event,'A2','B2',trans('common.created_date'),$this->data['created_date']);
        $this->setInfoValue($event,'A3','B3',trans('common.description_label'),$this->data['description']);
        $this->setInfoValue($event,'A4','B4',trans('common.created_by'), $this->data['created_by']);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        foreach ($this->contentColumns as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension("$column")->setAutoSize(true);
        }

        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue(trans('common.name_label'));
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.description_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.currency_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.amount_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.amount_base_currency'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.created_date'));
        $event->sheet->getDelegate()->getCell("G$this->headingRowIndex")->setValue(trans('common.created_by'));
        return $event;
    }

    public function setData(AfterSheet $event)
    {
        $this->setInfoValue($event,'D4','E4',trans('common.total_amount_label'), $this->query->sum('amount_in_base_currency')/100);
    }
}
