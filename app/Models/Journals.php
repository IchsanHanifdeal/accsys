<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journals extends Model
{
    protected $table = 'journals';
    protected $fillable = ['journal_number', 'transaction_date', 'description', 'reference_no', 'status', 'created_by'];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntries::class, 'journal_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Account::class, 'created_by');
    }
}
