<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Items;
use Carbon\Carbon;
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
        // TODO: implement to fetch the items with the category and rate
        $categories         = Category::with('items.rate')->where('is_deleted', 0)->get();
        $items              = [];
        $barcode_item       = NULL;
        $selectedCategory   = null;
        $history            = Cart::with('items' , 'items.item')
                                    ->where('is_deleted' , '=' , 0)
                                    ->orderBy('id', 'DESC')
                                    ->take(10)
                                    ->get();
        // dd($history->toArray());

        if ($request->has('category_id') && $request->category_id) {
            $selectedCategory = Category::with('items', 'items.currency' ,'items.rate', 'items.rate.currency' , 'items.rate.counter_currency')
                                            ->find($request->category_id);
            $items = $selectedCategory ? $selectedCategory->items : []; 
        // dd($items->toArray() );
        }
        if( $request->has('barcode') && $request->barcode)
        {
            $barcode_item = Items::with('category', 'brand', 'currency', 'rate', 'rate.currency', 'rate.counter_currency')
                                    ->where('bar_code', $request->barcode)
                                    ->first() ?? NULL;
        }
        if($request->has('EOTD') && $request->EOTD)
        {
            $invoices = Invoice::with('cart' , 'cart.items' ,'cart.items.item.rate.currency', 'cart.items.item.rate.counter_currency')
                                    ->whereDate('created_at', Carbon::today())
                                    ->where('is_deleted' , 0)
                                    ->where('user_id' , Auth::user()->id)
                                    ->get();
            if($invoices->count() != 0)
            {

                $invoice = $invoices->first();
                $cart = $invoice?->cart;
                $item = $cart?->items->first()?->item;
                $rate = $item?->rate;
                $currency = $rate->currency->name;
                $counter_currency = $rate->counter_currency->name;
                $rate = $rate->rate;
                $total = number_format($invoices->sum('amount'), 2);
                $total_sales_currency       = $currency.' '.$total;
                $total_sales_cCurrency      = $counter_currency.' '.number_format($total * $rate);
                $total_orders               = $invoices->count();
                $total_items                = $invoices->sum(function ($invoice) {
                    return $invoice->cart?->items->sum('quantity') ?? 0;
                });

                $total_sales = [
                    'total_sales_currency' => $total_sales_currency,
                    'total_sales_cCurrency'=> $total_sales_cCurrency,
                    'total_orders'          => $total_orders,
                    'total_items'           => $total_items
                ];
            }
            else
            {
                $total_sales = [
                    'total_sales_currency' => '0.00',
                    'total_sales_cCurrency'=> '0.00',
                    'total_orders'          => 0,
                    'total_items'           => 0
                ];
            }

        }

        return Inertia::render('Dashboard', [
            'categories'        => $categories , 
            'items'             => $items,
            'selectedCategory'  => $selectedCategory,
            'history'           => $history,
            'barcode_item'      => $barcode_item,
            'total_sales'       => $total_sales ?? 0
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
            $cart->user_id      = Auth::user()->id;

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
            // create invoice
            $invoice = new Invoice();
            $invoice->cart_id       = $cart->id;
            $invoice->user_id       = Auth::user()->id;
            $invoice->rate_id       = $cart->rate_id;
            $invoice->invoice_number= 'INV-' . time() . '-' . $cart->id;
            $invoice->amount        = $cart->total_price;

            $invoice->save();

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
