<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::where('is_deleted' , DB::raw(0))->get();
        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
            $category = Category::create($request->all());
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully',
                'data' => $category
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Category creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully',
            'data' => Category::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $category = Category::findOrFail($id);

        DB::beginTransaction();

        try
        {
            $category->name = $request->name;
            $category->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message'=> 'Category updated successfully',
                'data' => $category
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'=> $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        $category->is_deleted = 1;
        $category->save();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    public function category_items(Request $request)
    {
        $category_items = Category::with('items')
                            ->where('is_deleted' , DB::raw(0))
                            ->has('items')
                            ->get();
        return response()->json([
            'status' => true,
            'message'=> 'Category items fetched successfully',
            'data'=> $category_items
        ]);
    }
}
