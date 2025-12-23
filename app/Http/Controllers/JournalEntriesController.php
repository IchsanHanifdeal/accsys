<?php

namespace App\Http\Controllers;

use App\Models\Journals; 

use Illuminate\Http\Request;

class JournalEntriesController extends Controller
{

    public function index(Request $request)
    {

        $query = Journals::with(['journalEntries.account', 'createdBy']);

        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate   = $request->input('end_date', date('Y-m-t'));

        $query->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('journal_number', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        $journals = $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10) 

            ->withQueryString(); 

        return view('dashboard.riwayat_jurnal', compact('journals'));
    }
}
