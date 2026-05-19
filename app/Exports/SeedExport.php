<?php

namespace App\Exports;

use App\Models\SeedInfo;
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
use PhpParser\Node\Stmt\If_;
use function Symfony\Component\Translation\t;



class SeedExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{
//    private $currencyFormat;
//    private $baseCurrencyName = 'SRD';
    private $memberName = null;
    private $currencyName = null;
    private $typeName = null;
    private $sumAmount;
    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->startRow = 9;
        $this->headingRowIndex = 8;
        $this->headingRow = 'A8:F8';
        $this->contentColumns = ['A','B','C','D','E','F'];
        if(isset($data['currency_id'])){
            $this->currencyName =  DB::table('currencies')->where('id',$data['currency_id'])->first()->code;
        }
        if(isset($data['member_id'])){
            $this->memberName = DB::table('member_info')->where('id',$data['member_id'])->first()->name;
        }
        if(isset($data['seed_type'])){
            $this->typeName = $data['seed_type'] == 1 ? trans('common.seed_type_tide') : trans('common.seed_type_special_seed');
        }
    }

    public function query()
    {
        $seedTypeTide = config('constants.SEED_TYPE_TIDE');
        $tideTran = trans('common.seed_type_tide');
        $specialSeedTran = trans('common.seed_type_special_seed');
        $specialSeedType = config('constants.SEED_TYPE_SPECIAL_SEED');
        $buildingSeedTran = trans('common.building_seed');
        $ifSQL = "IF(type_id = $seedTypeTide,'$tideTran',IF(type_id = $specialSeedType,'$specialSeedTran','$buildingSeedTran'))";
        $query = DB::table('seeds_info')->select(DB::raw($ifSQL),'member','currency',DB::raw("((amount+ 0.01)/100)as 'amount'"), DB::raw("((amount_in_base_currency+ 0.01)/100) as 'amount_in_base_currency'") ,'date')->orderBy('member','desc');
        if(isset($this->data['currency_id'])){
            $query->where('currency_id','=',$this->data['currency_id']);
        }
        if (isset($this->data['member_id'])){
            $query->where('member_id','=',$this->data['member_id']);
        }
        if(isset($this->data['from_date'])){
            $query->whereDate('date','>',$this->data['from_date']);

        }
        if(isset($this->data['to_date'])){
            $query->whereDate('date','<',$this->data['to_date']);
        }
        if(isset($this->data['seed_type'])){
            $query->where('type_id','=',$this->data['seed_type']);
        }
        $this->itemCount = $query->count() + 2;
        $this->sumAmount = $query->sum('amount_in_base_currency') / 100;
        $query->get();

        return $query;
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
        $this->setInfoValue($event,'A2','B2',trans('common.from_label'),isset($this->data['from_date']) ? $this->data['from_date']: trans('common.no_date_selected'));
        $this->setInfoValue($event,'A3','B3',trans('common.to_date'),isset($this->data['to_date']) ? $this->data['to_date']: trans('common.no_date_selected'));
        $this->setInfoValue($event,'A4','B4',trans('common.member_label'), isset($this->memberName) ? $this->memberName: trans('common.no_member_selected'));
        $this->setInfoValue($event,'A5','B5',trans('common.currency_label'),isset($this->currencyName ) ? $this->currencyName : trans('common.no_currency_selected'));
        $this->setInfoValue($event,'A6','B6',trans('common.type_label'),isset($this->typeName ) ? $this->typeName : trans('common.all_label'));

        $event->sheet->getDelegate()->getRowDimension($this->headingRowIndex)->setRowHeight(20);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        foreach ($this->contentColumns as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension("$column")->setAutoSize(true);
        }
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue(trans('common.type_label'));
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.member_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.currency_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.amount_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.amount_base_currency'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.date_label'));
        return $event;
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY
        ];
    }

    public function setData(AfterSheet $event)
    {
       for($count = 0; $count <= $this->itemCount +1; $count++){
            $row = $this->startRow + $count;
            $event->sheet->getDelegate()->getRowDimension($row)->setRowHeight(20);
        }
        $sumRow = $this->startRow + $this->itemCount - 1;
        $event->sheet->getDelegate()->getStyle("E$sumRow")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $this->setInfoValue($event,"D$sumRow","E$sumRow",trans('common.total_amount_label'),$this->sumAmount);
    }

}
