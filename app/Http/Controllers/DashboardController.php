<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Items;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::where('is_deleted', 0)->get();
        $items = [];
        $selectedCategory = null;

        if ($request->has('category_id') && $request->category_id) {
            $selectedCategory = Category::with('items')
                                            ->find($request->category_id);
            $items = $selectedCategory ? $selectedCategory->items : []; 
        }
        // dd($items->toArray() , $selectedCategory->toArray());
        return Inertia::render('Dashboard', [
            'categories' => $categories , 
            'items' => $items,
            'selectedCategory' => $selectedCategory,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
