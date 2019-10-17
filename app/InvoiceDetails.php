<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    protected $table = 'invoice_details';
    protected $connection = 'client';

    protected $fillable = ['quantity', 'price', 'total', 'product_id', 'invoice_id'];

}
