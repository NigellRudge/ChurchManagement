<?php

namespace App\Exports;

use App\Models\Currency;
use App\Models\SubAccount;
use App\Models\Transaction;
use App\Models\TransactionInfo;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TransactionOverviewExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{

    private $accountName = null;
    private $currencyName = null;
    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->contentColumns = ['A','B','C','D','E','F','G','H'];
        $this->startRow = 8;
        $this->headingRowIndex = 7;
        $this->headingRow = 'A7:H7';
        if(isset($data['currency_id'])){
            $this->currencyName = Currency::find($data['currency_id'])->code;
        }
        if(isset($data['account_id'])){
            $this->accountName = SubAccount::find($data['account_id'])->name;
        }
    }

    public function query()
    {
        //dd($this->data);
        $expenseTran = trans('common.expense');
        $incomeTran = trans('common.income');
        $items = DB::table('transaction_info')->select(/*'id',*/'description','account',DB::raw("IF(tran_type = 1,'$incomeTran','$expenseTran') as 'type'") ,'currency',DB::raw("(amount + 0.00)/100"),'transaction_date','created_by','created_at')->orderBy('transaction_date');
        if(isset($this->data['currency_id'])){
            $items->where('currency_id','=',$this->data['currency_id']);
        }
        if(isset($this->data['from_date'])){
            $items->where('transaction_date','>=',Carbon::parse($this->data['from_date']));
        }
        if(isset($this->data['to_date'])){
            $items->where('transaction_date','<=',Carbon::parse($this->data['to_date']));
        }
        if(isset($this->data['account_id'])){
            $items->where('account_id','=',$this->data['account_id']);
        }
        $this->itemCount = $items->count();
        return $items;
    }

    public function columnFormats(): array
    {
        return [
            "E" => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            "F" => NumberFormat::FORMAT_DATE_DMYSLASH,
            "H" => NumberFormat::FORMAT_DATE_DMYSLASH,
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
        $this->setInfoValue($event,'A2','B2',trans('common.from_label'),isset($this->data['from_date']) ? $this->data['from_date']: trans('common.no_date_selected'));
        $this->setInfoValue($event,'A3','B3',trans('common.to_date'),isset($this->data['to_date']) ? $this->data['to_date']: trans('common.no_date_selected'));
        $this->setInfoValue($event,'A4','B4',trans('common.account'), isset($this->accountName) ? $this->accountName: trans('common.no_member_selected'));
        $this->setInfoValue($event,'A5','B5',trans('common.currency_label'),isset($this->currencyName ) ? $this->currencyName : trans('common.no_currency_selected'));
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        foreach ($this->contentColumns  as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension("$column")->setAutoSize(true);
        }
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue(trans('common.description_label'));
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.account'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.type_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.currency_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.amount_label'));
        $event->sheet->getDelegate()->getCell("F$this->headingRowIndex")->setValue(trans('common.transaction_date'));
        $event->sheet->getDelegate()->getCell("G$this->headingRowIndex")->setValue(trans('common.created_by'));
        $event->sheet->getDelegate()->getCell("H$this->headingRowIndex")->setValue(trans('common.created_date'));
        return $event;
    }
}
