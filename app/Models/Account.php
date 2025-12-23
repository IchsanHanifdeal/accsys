<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $table = 'accounts';
    protected $fillable = ['account_type_id', 'code', 'name', 'description', 'is_active'];

    public function accountType()
    {
        return $this->belongsTo(AccountTypes::class, 'account_type_id');
    }
}
