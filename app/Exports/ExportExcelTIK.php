<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportExcelTIK implements FromView
{
    protected $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('export.export-tik', [
            'data' => $this->data,
        ]);
    }
}
