<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FinanceOverReportExport extends BaseExport implements FromView, WithEvents, WithColumnFormatting,WithStrictNullComparison
{
    private $incomeHeaderCel = 'A3';
    private $expenseHeaderCel = '';
    private $sumIncome = 0;
    private $sumExpense = 0;
    private $incomeTableStartRow = 5;
    private $sumIncomeTitleCel = '';
    private $sumIncomeValueCel = '';
    private $sumExpenseTitleCel = '';
    private $sumExpenseValueCel = '';
    private $timePeriod = '';

    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);

    }

    public function view(): View
    {
        $start = now()->firstOfMonth();
        $end = now()->endOfMonth();
        if(isset($this->data['from_date'])){
           $start = Carbon::parse($this->data['from_date']);
        }
        if(isset($this->data['to_date'])){
            $end = Carbon::parse($this->data['to_date']);
        }
        $tranTotals = DB::table('transactions')
            ->select(DB::raw("if(sum(amount) is null,0,sum(amount)) as 'total'"),'account_id')
            ->groupBy('account_id')
            ->where('transaction_date' ,'>=',$start->toDateString())
            ->where('transaction_date' ,'<=',$end->toDateString());
        $timePeriod = $start->toDateString() . ' - ' . $end->toDateString();
        $items = DB::table('sub_accounts')
            ->leftJoinSub($tranTotals,'tran_totals',function ($join){
                $join->on('sub_accounts.id','=','tran_totals.account_id');
            })
            ->leftJoin(DB::raw('main_accounts ma'),'sub_accounts.parent_account_id','=','ma.id')
            ->leftJoin(DB::raw('currencies c'),'ma.currency_id','=','c.id')
            ->select('sub_accounts.id',
                'sub_accounts.name',
                'ma.currency_id',
                'sub_accounts.can_delete',
                DB::raw("c.code as 'currency'"),
                DB::raw("ma.name as 'parent_account'"),
                DB::raw("if(isnull(sub_accounts.deleted_at), 1, 0) AS 'status'"),
                DB::raw("tran_totals.total as 'balance'"),
                DB::raw("(tran_totals.total * c.exchange_rate) as 'balance_srd'"),
                DB::raw("ma.account_type as 'account_type'")
            )->get();
        $incomeAccounts = $items->where('account_type','=',config('constants.MAIN_ACCOUNT_TYPE_INCOME'));
        $expenseAccounts =  $items->where('account_type','=',config('constants.MAIN_ACCOUNT_TYPE_EXPENSE'));
        $this->expenseHeaderCel = 'A' . strval($incomeAccounts->count() + 7);
        $this->sumIncome = $incomeAccounts->sum('balance_srd') /100;
        $this->sumExpense = $expenseAccounts->sum('balance_srd') /100;

        $this->sumIncomeTitleCel = 'C' . strval($incomeAccounts->count() + $this->incomeTableStartRow);
        $this->sumIncomeValueCel = 'D' . strval($incomeAccounts->count() + $this->incomeTableStartRow);

        $this->sumExpenseTitleCel = 'C' . strval($incomeAccounts->count() + $expenseAccounts->count() + 9);
        $this->sumExpenseValueCel = 'D' . strval($incomeAccounts->count() + $expenseAccounts->count() + 9);

        return view('subaccounts.FinanceOverViewReport', [
            'income_accounts' => $incomeAccounts,
            'expense_accounts' => $expenseAccounts,
            'period' => $timePeriod
        ]);
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => [$this,'test']
        ];
    }

    public function test(AfterSheet $event){
        $titleRow = 'A1:D1';
        $subTitleRow = 'A2:D2';
        $incomeHeaderCel = 'A3';
        $event->sheet->getDelegate()->mergeCells($titleRow);
        $event->sheet->getDelegate()->mergeCells($subTitleRow);
        $event->sheet->getDelegate()->getStyle($titleRow)->getActiveSheet()->getStyle($titleRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle($subTitleRow)->getActiveSheet()->getStyle($subTitleRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $event->sheet->getDelegate()->getStyle($titleRow)->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($titleRow)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($subTitleRow)->getFont()->setSize(14);
        $event->sheet->getDelegate()->getStyle($subTitleRow)->getFont()->setBold(true);


        $event->sheet->getDelegate()->getStyle($this->incomeHeaderCel)->getFont()->setSize(12);
        $event->sheet->getDelegate()->getStyle($this->incomeHeaderCel)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->incomeHeaderCel)->getFont()->setUnderline(true);

        $event->sheet->getDelegate()->getStyle($this->expenseHeaderCel)->getFont()->setSize(12);
        $event->sheet->getDelegate()->getStyle($this->expenseHeaderCel)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->expenseHeaderCel)->getFont()->setUnderline(true);

        $event->sheet->getDelegate()->getStyle('A')->getActiveSheet()->getColumnDimension("A")->setWidth(50);
        $event->sheet->getDelegate()->getStyle('C')->getActiveSheet()->getColumnDimension("C")->setWidth(25);
        $event->sheet->getDelegate()->getStyle('D')->getActiveSheet()->getColumnDimension("D")->setWidth(25);

        $event->sheet->getDelegate()->getStyle($this->sumIncomeTitleCel)->getFont()->setSize(12);
        $event->sheet->getDelegate()->getStyle($this->sumIncomeTitleCel)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->sumIncomeTitleCel)->getFont()->setUnderline(true);
        $event->sheet->getDelegate()->getCell($this->sumIncomeTitleCel)->setValue(trans('common.total_amount_label'));
        $event->sheet->getDelegate()->getCell($this->sumIncomeValueCel)->setValue($this->sumIncome);

        $event->sheet->getDelegate()->getStyle($this->sumExpenseTitleCel)->getFont()->setSize(12);
        $event->sheet->getDelegate()->getStyle($this->sumExpenseTitleCel)->getFont()->setBold(true);
        $event->sheet->getDelegate()->getStyle($this->sumExpenseTitleCel)->getFont()->setUnderline(true);
        $event->sheet->getDelegate()->getCell($this->sumExpenseTitleCel)->setValue(trans('common.total_amount_label'));
        $event->sheet->getDelegate()->getCell($this->sumExpenseValueCel)->setValue($this->sumExpense);
        $event->sheet->getDelegate()->getStyle($this->sumExpenseValueCel)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $event->sheet->getDelegate()->getCell('G4')->setValue(trans('common.total_income'));
        $event->sheet->getDelegate()->getCell('H4')->setValue($this->sumIncome);
        $event->sheet->getDelegate()->getStyle('H4')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $event->sheet->getDelegate()->getStyle('G')->getActiveSheet()->getColumnDimension("G")->setWidth(25);
        $event->sheet->getDelegate()->getStyle('H')->getActiveSheet()->getColumnDimension("H")->setWidth(20);
        $event->sheet->getDelegate()->getStyle('G4')->getFont()->setSize(12);
        $event->sheet->getDelegate()->getStyle('G4')->getFont()->setBold(true);


        $event->sheet->getDelegate()->getCell('G5')->setValue(trans('common.total_expense'));
        $event->sheet->getDelegate()->getCell('H5')->setValue($this->sumExpense);
        $event->sheet->getDelegate()->getStyle('H5')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $event->sheet->getDelegate()->getStyle('G5')->getFont()->setSize(12);
        $event->sheet->getDelegate()->getStyle('G5')->getFont()->setBold(true);

        $event->sheet->getDelegate()->getCell('G6')->setValue(trans('common.result'));
        $event->sheet->getDelegate()->getCell('H6')->setValue($this->sumIncome - $this->sumExpense);
        $event->sheet->getDelegate()->getStyle('H6')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        if(($this->sumIncome - $this->sumExpense) < 0){
            $event->sheet->getDelegate()->getStyle('H6')->getFont()->setColor(new Color(Color::COLOR_RED));
        }
        $event->sheet->getDelegate()->getStyle('G6')->getFont()->setSize(12);
        $event->sheet->getDelegate()->getStyle('G6')->getFont()->setBold(true);

    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}

