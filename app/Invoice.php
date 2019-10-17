<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $connection = 'client';

    protected $fillable = ['total', 'cash', 'returns'];
}
