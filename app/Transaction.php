<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
            'TransactionType','TransID','TransAmount','BusinessShortCode','MSISDN','FirstName','LastName'
    ];
}
