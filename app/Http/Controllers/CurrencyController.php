<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::where('is_deleted', 0)->get();

        return Inertia::render('currency/List', [
            'currencies' => $currencies,
        ]);
    }

    public function create()
    {
        return Inertia::render('currency/Create');
    }

    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name'=> 'required|string',
            'code'=> 'required|string|unique:currencies,code',
        ]);

        Currency::create($validated_data);
        return Inertia::location(route('currencies.index'));
    }

    public function edit($id)
    {
        return Inertia::render('currency/Edit', [
            'currency'=> Currency::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated_data = $request->validate([
            'name'=> 'required|string',
            'code'=> 'required|string|unique:currencies,code,' . $id,
        ]);

        $currency = Currency::findOrFail($id);
        $currency->update($validated_data);

        return $this->index();
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);

        $currency->is_deleted = 1;
        $currency->save();
        // dd($currency->update(['is_deleted' => 1]), $currency);   

        return $this->index();
    }
}
