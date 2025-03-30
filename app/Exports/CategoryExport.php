<?php

namespace App\Exports;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategoryExport implements  WithHeadings, ShouldAutoSize ,FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $categories;

    public function __construct($categories)
    {
        $this->categories = $categories;
    }

    public function collection()
    {
       return $this->categories->map(function ($category){
            return [
                'id' => $category->id,
                'name' => $category->name,
                'created_at' => Carbon::parse($category->created_at)->format('d-m-Y'),
                'updated_at' => Carbon::parse($category->updated_at)->format('d-m-Y'),
            ];
       });
    }
    
    public function headings(): array
    {
        return ["ID", "Name" , "Created At" , "Updated At"];
    }
}
