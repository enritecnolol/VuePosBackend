<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $connection = 'client';

    protected $fillable = [
        'name', 'price', 'barcode', 'img', 'existence', 'tax', 'itbis', 'categoria_id'
    ];
}
