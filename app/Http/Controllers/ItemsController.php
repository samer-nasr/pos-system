<?php

namespace App\Http\Controllers;

use App\Exports\ItemExport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Items::with('brand', 'category')->where('is_deleted' , DB::raw(0))->get();
        return Inertia::render('item/List', ['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_deleted' , DB::raw(0))
                                ->where('is_deleted' , DB::raw(0))
                                ->get();
        $brands = Brand::where('is_deleted' , DB::raw(0))
                                ->where('is_deleted' , DB::raw(0))
                                ->get();
        return Inertia::render('item/Create' , ['categories' => $categories , 'brands' => $brands]);
    }

    public function export()
    {
        $items = Items::with('brand' , 'category')->where('is_deleted' , DB::raw(0))->get();
        return Excel::download(new ItemExport($items), 'items.xlsx');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:50',
            'price'=> 'required|numeric',
            'quantity'=> 'required|numeric',
            'category'=> 'required|exists:categories,id|numeric',
            'brand'=> 'required|exists:brands,id|numeric',
            'bar_code' => 'required|string|min:3|unique:items,bar_code'
        ]);

        DB::beginTransaction();

        try
        {
            Items::create([
                'name' => $request->name,
                'price'=> $request->price,
                'quantity'=> $request->quantity,
                'category_id'=>$request->category,
                'brand_id'=>$request->brand,
                'bar_code'=> $request->bar_code
            ]);
            DB::commit();
            return $this->index();
        }
        catch(\Exception $e)
        {
            dd($e);
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Items $items)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $item = Items::with('brand', 'category')->find($id);


        $categories = Category::where('is_deleted' , DB::raw(0))
                                ->where('is_deleted' , DB::raw(0))
                                ->get();
        $brands = Brand::where('is_deleted' , DB::raw(0))
                                ->where('is_deleted' , DB::raw(0))
                                ->get();

        return Inertia::render('item/Edit', ['item' => $item, 'categories' => $categories , 'brands' => $brands]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Items $item)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'price'=> 'required|numeric',
            'quantity'=> 'required|numeric',
            'category'=> 'required|exists:categories,id|numeric',
            'brand'=> 'required|exists:brands,id|numeric',
        ]);

        DB::beginTransaction();

        try
        {
            $item->name = $request->name;
            $item->price = $request->price;
            $item->quantity = $request->quantity;
            $item->category_id = $request->category;
            $item->brand_id = $request->brand;
            $item->bar_code = $request->bar_code;
            $item->save();
            DB::commit();
            return $this->index();
        }
        catch(\Exception $e)
        {
            dd($e->getMessage());
            DB::rollBack();
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
            $item = Items::find($id);
            $item->is_deleted = 1;
            $item->save();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
