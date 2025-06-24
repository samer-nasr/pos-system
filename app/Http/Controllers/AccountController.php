<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Operation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\Operands\Operand;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::where('is_deleted' , 0)
                            ->get();
        return Inertia::render('accounts/List', [
            'accounts' => $accounts
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('accounts/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'string|nullable',
        ]);

        $account = new Account();
        $account->name = $request->name;
        $account->code = $request->code;

        $account->save();
        return $this->index($request);
    }

    public function edit(Request $request, $id)
    {
        $account = Account::findOrFail($id);
        $amount  = Operation::where('type' , 'like', 'C')
                                // ->where('is_deleted' , 0)
                                ->where('origin' , 'like' , 'account')
                                ->where('origin_id' , $id)
                                ->sum('amount'); 

        return Inertia::render('accounts/Edit' , [
            'account' => $account,
            'amount' => $amount
        ]);
    }

    public function update(Request $request , $id)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'string|nullable'
        ]);

        $account = Account::findOrFail($id);
        $account->name = $request->name;
        $account->code = $request->code;

        $account->save();
        return $this->index($request);
    }
}
