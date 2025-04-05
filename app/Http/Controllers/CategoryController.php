<?php

namespace App\Http\Controllers;

use App\Exports\CategoryExport;
use App\Imports\CategoryImport;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('is_deleted' , DB::raw(0))->get();
        return Inertia::render('category/List', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('category/Create');
    }

    public function export()
    {
        $categories = Category::where('is_deleted' , DB::raw(0))->get();
        return Excel::download(new CategoryExport($categories) , 'categories.xlsx');
    }

    public function import(Request $request)
    {
         $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // Import the categories from the uploaded file
        Excel::import(new CategoryImport, $request->file('file'));
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
            Category::create($request->all());
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
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return Inertia::render('category/Edit',['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        DB::beginTransaction();

        try
        {
            $category->name = $request->name;
            $category->save();

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
            $category = Category::find($id);
            $category->is_deleted = 1;
            $category->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    
    }
}
