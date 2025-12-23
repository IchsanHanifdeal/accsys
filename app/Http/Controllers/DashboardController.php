<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journals;
use App\Models\JournalEntries;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $totalAset = JournalEntries::whereHas('account.accountType', function ($q) {
            $q->where('name', 'like', '%Aset%'); 

        })->whereHas('journal', function ($q) {
            $q->where('status', 'posted');
        })->sum(DB::raw('debit - credit')); 

        $pendapatanBulanIni = JournalEntries::whereHas('account.accountType', function ($q) {
            $q->where('name', 'like', '%Pendapatan%');
        })->whereHas('journal', function ($q) {
            $q->where('status', 'posted')
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->whereYear('transaction_date', Carbon::now()->year);
        })->sum(DB::raw('credit - debit')); 

        $bebanBulanIni = JournalEntries::whereHas('account.accountType', function ($q) {
            $q->where('name', 'like', '%Beban%');
        })->whereHas('journal', function ($q) {
            $q->where('status', 'posted')
                ->whereMonth('transaction_date', Carbon::now()->month)
                ->whereYear('transaction_date', Carbon::now()->year);
        })->sum(DB::raw('debit - credit'));

        $labaBersih = $pendapatanBulanIni - $bebanBulanIni;

        $chartLabels = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartLabels[] = $date->format('M'); 

            $pemasukan = JournalEntries::whereHas('account', function ($q) {
                $q->where('code', '101'); 

            })->whereHas('journal', function ($q) use ($date) {
                $q->where('status', 'posted')
                    ->whereMonth('transaction_date', $date->month)
                    ->whereYear('transaction_date', $date->year);
            })->sum('debit');

            $pengeluaran = JournalEntries::whereHas('account', function ($q) {
                $q->where('code', '101'); 

            })->whereHas('journal', function ($q) use ($date) {
                $q->where('status', 'posted')
                    ->whereMonth('transaction_date', $date->month)
                    ->whereYear('transaction_date', $date->year);
            })->sum('credit');

            $chartPemasukan[] = $pemasukan / 1000000; 

            $chartPengeluaran[] = $pengeluaran / 1000000;
        }

        $draftJournals = Journals::where('status', 'draft')->latest()->take(5)->get();
        $recentJournals = Journals::where('status', 'posted')->latest('transaction_date')->take(5)->get();

        return view('dashboard.index', compact(
            'totalAset',
            'pendapatanBulanIni',
            'bebanBulanIni',
            'labaBersih',
            'chartLabels',
            'chartPemasukan',
            'chartPengeluaran',
            'draftJournals',
            'recentJournals'
        ));
    }
}
