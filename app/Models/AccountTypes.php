<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountTypes extends Model
{
    protected $table = 'account_types';

    protected $fillable = ['name', 'normal_balance'];
}
