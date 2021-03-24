<?php

namespace App\Exports;

use App\Models\Spm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SPMExport implements FromView, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     return Spm::all();
    // }

    use RegistersEventListeners;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('excel.spm', [
            'data' => $this->data
        ]);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Surname',
            'Email',
            'Twitter',
        ];
    }

    public static function afterSheet(AfterSheet $event)
    {
        return "<script>window.close();</script>";
    }
}
