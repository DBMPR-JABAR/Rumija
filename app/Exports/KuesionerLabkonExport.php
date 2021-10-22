<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KuesionerLabkonExport implements FromCollection, WithHeadings
{use Exportable;
    private $kuesioner, $id_permohonan;

    public function __construct(array $kuesioner, string $id_permohonan)
    {
        $this->kuesioner = $kuesioner;
        $this->id_permohonan = $id_permohonan;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->kuesioner);
    }
    public function headings() : array
    {
        return ["No Permohonan", str_replace("-", "/", $this->id_permohonan)];
    }
}
