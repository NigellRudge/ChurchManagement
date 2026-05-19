<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class TestTransactionExport extends BaseExport implements FromView, WithEvents, WithColumnFormatting,WithStrictNullComparison
{

    public function __construct($title, array $data)
    {
        parent::__construct($title, $data);
    }

    public function view(): View
    {
        // TODO: Implement view() method.
    }

    public function columnFormats(): array
    {
        // TODO: Implement columnFormats() method.
    }

    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
    }
}
