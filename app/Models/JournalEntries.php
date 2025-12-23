<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntries extends Model
{
    protected $table = 'journal_entries';
    protected $fillable = ['journal_id', 'account_id', 'debit', 'credit'];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journals::class, 'journal_id');
    }
}
