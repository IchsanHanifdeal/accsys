<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountTypes;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {

        $query = Account::with('accountType');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($request->has('type_id') && $request->type_id != '') {
            $query->where('account_type_id', $request->type_id);
        }

        $accounts = $query->orderBy('code', 'asc')
            ->paginate(10)
            ->withQueryString(); 

        $account_types = AccountTypes::all();

        return view('dashboard.account', [
            'accounts' => $accounts,
            'account_types' => $account_types,
            'title' => 'Daftar Akun (COA)' 

        ]);
    }
}
