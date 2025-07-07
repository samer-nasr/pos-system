<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\Operands\Operand;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::where('is_deleted' , 0)
                            ->get();

        $total = Operation::where('is_deleted' , 0)
                    ->where('origin', 'like', 'item')
                    ->selectRaw("
                        SUM(
                            CASE 
                                WHEN type = 'C' THEN amount
                                WHEN type = 'D' THEN -amount
                                ELSE 0
                            END
                        ) as total
                    ")
                    ->value('total');
        return Inertia::render('accounts/List', [
            'accounts' => $accounts,
            'total' => $total
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
        $account_info = Operation::select([
                                'account_ref',
                                DB::raw('SUM(CASE WHEN type = "D" THEN amount ELSE 0 END) as total_debits'),
                                DB::raw('SUM(CASE WHEN type = "C" THEN amount ELSE 0 END) as total_credits'),
                                DB::raw('SUM(CASE WHEN type = "D" THEN amount ELSE 0 END) - SUM(CASE WHEN type = "C" THEN amount ELSE 0 END) as balance'),
                            ])
                            ->where('account_ref' , 'like' , $account->code)
                            ->groupBy('account_ref')
                            ->get()->first();
        // dd($account_info->toArray());

        return Inertia::render('accounts/Edit' , [
            'account' => $account,
            'account_info' => $account_info
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
