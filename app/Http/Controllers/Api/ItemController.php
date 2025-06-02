<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Items::where('is_deleted' , DB::raw(0))->get();
        return response()->json([
            'status' => true,
            'message' => 'Items fetched successfully',
            'data' => $items
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
            'price'=> 'required|numeric',
            'quantity'=> 'required|numeric',
            'category'=> 'required|exists:categories,id|numeric',
            'brand'=> 'required|exists:brands,id|numeric',
        ]);

        DB::beginTransaction();
        
        try
        {
            $item = Items::create([
                'name' => $request->name,
                'price'=> $request->price,
                'quantity'=> $request->quantity,
                'category_id'=> $request->category,
                'brand_id'=> $request->brand,
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Item created successfully',
                'data' => $item
            ]);
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Item creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try
        {
            return response()->json([
                'status' => true,
                'message' => 'Item fetched successfully',
                'data' => Items::findOrFail($id)
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'Item could not be fetched!',
            ]);
        }
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
            'name' => 'string|max:50',
            'price'=> 'numeric',
            'quantity'=> 'numeric',
            'category'=> 'exists:categories,id|numeric',
            'brand'=> 'exists:brands,id|numeric',
        ]);

       

        DB::beginTransaction();

        try
        {
            if(count($request->all()) == 0) throw new \Exception('No data to update');
            
            $item = Items::findOrFail($id);
            $item->name = $request->name ?? $item->name;
            $item->price = $request->price ?? $item->price;
            $item->quantity = $request->quantity ?? $item->quantity;
            $item->category_id = $request->category ?? $item->category_id;
            $item->brand_id = $request->brand ?? $item->brand_id;
    
            $item->save();

            DB::commit();
            return response()->json([
                'status' => true,
                'message'=> 'Item updated successfully',
                'data' => $item
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
        try
        {
            $item = Items::findOrFail($id);
            $item->is_deleted = 1;
            $item->save();

            return response()->json([
                'status' => true,
                'message' => 'Item deleted successfully',
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => false,
                'message' => 'Item could not be deleted',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function item_category(Request $request)
    {
        $item_category = Items::with(['category' => function ($query) 
                                    {
                                        $query->select('id', 'name');
                                        // $query->where('name' , '!=' , 'drinks');
                                    }])
                                    ->where('is_deleted', DB::raw(0))
                                    ->has('category')
                                    ->get();
        
        return response()->json([
            'status' => true,
            'message' => 'Item categories fetched successfully',
            'data' => $item_category
        ]);
    }
}
