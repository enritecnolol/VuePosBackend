<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $connection = 'client';

    protected $fillable = [
        'name'
    ];
}
