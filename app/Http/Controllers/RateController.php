<?php

namespace App\Http\Controllers;

use App\Models\rate;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RateController extends Controller
{
    public function index()
    {
        $rates = rate::where('is_deleted', 0)->get();
        return Inertia::render('rate/List' , [
            'rates' => $rates
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('rate/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:10',
            'counter_currency' => 'required|string|max:10',
            'rate' => 'required|numeric',
        ]);

        rate::create([
            'currency' => $request->currency,
            'counter_currency' => $request->counter_currency,
            'rate' => $request->rate,
            'is_deleted'=> 0
        ]);

        return Inertia::location(route('rates.index'));
    }

    public function edit($id)
    {
        return Inertia::render('rate/Edit', [
            'rate' => rate::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'currency' => 'required|string|max:10',
            'counter_currency' => 'required|string|max:10',
            'rate' => 'required|numeric',
        ]);

        $rate = rate::findOrFail($id);
        $rate->update([
            'currency' => $request->currency,
            'counter_currency' => $request->counter_currency,
            'rate' => $request->rate,
        ]);

         return Inertia::location(route('rates.index'));
    }
}
