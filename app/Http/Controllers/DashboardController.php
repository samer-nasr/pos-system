<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Items;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function pay(Request $request)
    {
        $items          = $request->items;
        $total_price    = $request->total_price;
        $rate_id        = $items[0]['rate_id'];
        $number_of_items= count($items);

        // dd($items , $total_price, $number_of_items, $rate_id);
        
        DB::beginTransaction();
        try
        {
            $cart               = new Cart();
            $cart->total_price  = $total_price;
            $cart->user_id      = Auth::user()->id;
            $cart->rate_id      = $rate_id;
            $cart->item_quantity= $number_of_items;

            $cart->save();

            foreach($items as $item)
            {
                $db_item            = Items::find($item['id']);

                $db_item->quantity  -= $item['order_quantity'];

                $db_item->save();

                $cart_item          = new CartItem();
                $cart_item->cart_id = $cart->id;
                $cart_item->item_id = $item['id'];
                $cart_item->quantity= $item['order_quantity'];

                $cart_item->save();
            }
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            dd($e);
        }

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
