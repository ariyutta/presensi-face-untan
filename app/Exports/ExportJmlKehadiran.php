<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportJmlKehadiran implements FromView
{
    protected $data;

    public function __construct(object $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('export.jml-kehadiran', [
            'data' => $this->data,
        ]);
    }
}
