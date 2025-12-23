<?php

namespace App\Http\Controllers;

use App\Models\AccountTypes;
use Illuminate\Http\Request;

class AccountTypesController extends Controller
{
    public function index()
    {
        $tipe_akun = AccountTypes::all();
        return view('dashboard.account_type', [
            'tipe_akun' => $tipe_akun,
        ]);
    }
}
