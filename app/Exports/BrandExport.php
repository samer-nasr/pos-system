<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BrandExport implements  WithHeadings, ShouldAutoSize,FromCollection
{
    protected $brands;

    public function __construct($brands)
    {
        $this->brands = $brands;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return $this->brands->map(function ($brand){
            return [
                'id' => $brand->id,
                'name' => $brand->name,
                'created_at' => Carbon::parse($brand->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($brand->updated_at)->format('d-m-Y'),
            ];
       });
    }

    public function headings(): array
    {
        return ["ID", "Name" , "Created At" , "Updated At"];
    }
}
