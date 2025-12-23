<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journals; 

use App\Models\JournalEntries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalsController extends Controller
{
    public function index()
    {

        $accounts = Account::where('is_active', true)->orderBy('code')->get();

        $recent_journals = Journals::with('journalEntries.account')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(5);

        $next_number = 'JRN-' . date('Ym') . '-' . str_pad(Journals::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('dashboard.journal.index', compact('accounts', 'recent_journals', 'next_number'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'description'      => 'required|string',
            'details'          => 'required|array|min:2', 

            'details.*.account_id' => 'required|exists:accounts,id',
            'details.*.debit'  => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $journal = Journals::create([
                    'journal_number'   => $request->journal_number,
                    'transaction_date' => $request->transaction_date,
                    'description'      => $request->description,
                    'reference_no'     => $request->reference_no,
                    'status'           => 'posted', 

                    'created_by'       => Auth::id() ?? 1,
                ]);

                $totalDebit = 0;
                $totalCredit = 0;

                foreach ($request->details as $detail) {

                    if ($detail['debit'] == 0 && $detail['credit'] == 0) continue;

                    JournalEntries::create([
                        'journal_id' => $journal->id,
                        'account_id' => $detail['account_id'],
                        'debit'      => $detail['debit'],
                        'credit'     => $detail['credit'],
                    ]);

                    $totalDebit += $detail['debit'];
                    $totalCredit += $detail['credit'];
                }

                if ($totalDebit != $totalCredit) {
                    throw new \Exception("Jurnal tidak seimbang! Debit: $totalDebit, Kredit: $totalCredit");
                }
            });

            return redirect()->route('jurnal_umum')->with('success', 'Jurnal berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
