<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemExport implements FromCollection , ShouldAutoSize , WithHeadings
{
    protected $items;

    public function __construct($items)
    {
        $this->items = $items;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->items->map(function ($item){
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'category' => $item->category->name,
                'brand' => $item->brand->name
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Price',
            'Quantity',
            'Category',
            'Brand',
        ];
    }
}
