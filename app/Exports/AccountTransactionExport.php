<?php

namespace App\Exports;

use App\Models\TransactionInfo;
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




class AccountTransactionExport extends BaseExport implements FromQuery, WithTitle, WithCustomStartCell, WithEvents, WithColumnFormatting
{
    private $sumDebit = 0;
    private $sumCredit = 0;
    private $balance = null;
    private $accountType = null;
    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
        $this->startRow = 9;
        $this->headingRowIndex = 8;
        $this->headingRow = 'A8:E8';
        $this->contentColumns = ['A','B','C','D','E'];
        $this->accountType = $data['account']['account_type'] == 1 ? trans('common.income_type_account') : trans('common.expense_type_account');
    }

    public function query()
    {
        $query = DB::table('transaction_info')->where('account_id','=',$this->data['account_id'])
            ->select('description','transaction_date','currency',DB::raw("((amount + 0.00) /100) as 'amount'"),'created_by')->orderBy('transaction_date','desc');
        if(isset($this->data['from_date'])){
            $query->whereDate('transaction_date','>',$this->data['from_date']);
        }
        if(isset($this->data['to_date'])){
            $query->whereDate('transaction_date','<',$this->data['to_date']);
        }
        $temp = $query;
        $this->balance = $temp->sum('amount') /100;
        $this->itemCount = $query->count() + 1;
        return $query;
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
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
        $this->setInfoValue($event,'A1','B1',trans('common.account'),$this->data['account']['name']);
        $this->setInfoValue($event,'A2','B2',trans('common.from_label'),isset($this->data['from_date']) ? $this->data['from_date']: trans('common.no_date_selected'));
        $this->setInfoValue($event,'A3','B3',trans('common.to_date'),isset($this->data['to_date']) ? $this->data['to_date']: trans('common.no_date_selected'));
        $this->setInfoValue($event,'A4','B4',trans('common.main_account'), $this->data['account']['parent_account']);
        $this->setInfoValue($event,'A5','B5',trans('common.currency_label'),$this->data['account']['currency']);
        $this->setInfoValue($event,'A6','B6',trans('common.account_type'),$this->accountType);
        $event->sheet->getDelegate()->getStyle($this->headingRow)->getFont()->setBold(true);
        foreach ($this->contentColumns as $column){
            $event->sheet->getDelegate()->getStyle($this->headingRow)->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }
        $event->sheet->getDelegate()->getCell("A$this->headingRowIndex")->setValue(trans('common.description_label'));
        $event->sheet->getDelegate()->getCell("B$this->headingRowIndex")->setValue(trans('common.date_label'));
        $event->sheet->getDelegate()->getCell("C$this->headingRowIndex")->setValue(trans('common.currency_label'));
        $event->sheet->getDelegate()->getCell("D$this->headingRowIndex")->setValue(trans('common.amount_label'));
        $event->sheet->getDelegate()->getCell("E$this->headingRowIndex")->setValue(trans('common.created_by'));
        return $event;
    }

    public function setData(AfterSheet $event)
    {
        $rowIndex = $this->itemCount + $this->startRow;
        $event->sheet->getDelegate()->setCellValue("D$rowIndex",$this->balance);
        $event->sheet->getDelegate()->getStyle("C$rowIndex")->getFont()->setBold(true);
        $event->sheet->getDelegate()->setCellValue("C$rowIndex",trans('common.total'));
        $event->sheet->getDelegate()->getStyle("D$rowIndex")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

    }
}
