<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\rate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RateController extends Controller
{
    public function index()
    {
        $rates = rate::with('currency' , 'counter_currency')
                        ->where('is_deleted', 0)
                        ->get();
        return Inertia::render('rate/List' , [
            'rates' => $rates
        ]);
    }

    public function create(Request $request)
    {
        $currency = Currency::where('is_deleted', 0)
                            ->get();

        $counter_currency = Currency::where('is_deleted', 0)
                            ->get();
        return Inertia::render('rate/Create', [
            'currency'=> $currency,
            'counter_currency'=> $counter_currency
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency' => 'required|numeric',
            'counter_currency' => 'required|numeric',
            'rate' => 'required|numeric',
        ]);

        rate::create([
            'currency_id' => $request->currency,
            'counter_currency_id' => $request->counter_currency,
            'rate' => $request->rate,
            'is_deleted'=> 0
        ]);

        return Inertia::location(route('rates.index'));
    }

    public function edit($id)
    {
        $rate = rate::findOrFail($id);
        $currency = Currency::where('is_deleted', 0)
                            ->get();

        $counter_currency = Currency::where('is_deleted', 0)
                            ->get();
        return Inertia::render('rate/Edit', [
            'rate' => $rate,
            'currency'=> $currency,
            'counter_currency'=> $counter_currency
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'currency' => 'required|numeric',
            'counter_currency' => 'required|numeric',
            'rate' => 'required|numeric',
        ]);

        $rate = rate::findOrFail($id);
        $rate->update([
            'currency_id' => $request->currency,
            'counter_currency_id' => $request->counter_currency,
            'rate' => $request->rate,
        ]);

         return Inertia::location(route('rates.index'));
    }
}
