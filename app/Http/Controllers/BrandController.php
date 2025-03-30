<?php

namespace App\Http\Controllers;

use App\Exports\BrandExport;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::where('is_deleted' , DB::raw(0))->get();
        return Inertia::render('brand/List', ['brands' => $brands]);    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('brand/Create');
    }

    public function export()
    {
        $brands = Brand::where('is_deleted' , DB::raw(0))->get();
        return Excel::download(new BrandExport($brands) , 'brands.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try
        {
            Brand::create($request->all());
            DB::commit();
            return $this->index();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $brand = Brand::find($id);
        return Inertia::render('brand/Edit', ['brand' => $brand]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try
        {
            $brand->name = $request->name;
            $brand->save();
            DB::commit();
            return $this->index();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $brand = Brand::find($id);
            $brand->is_deleted = 1;
            $brand->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
