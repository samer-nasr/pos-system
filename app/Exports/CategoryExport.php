<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategoryExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Category::query()->select('id', 'name', 'created_at', 'updated_at')->where('is_deleted', DB::raw(0));
    }

    public function headings(): array
    {
        return ["ID", "Name" , "Created At" , "Updated At"];
    }
}
