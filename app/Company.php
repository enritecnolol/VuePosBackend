<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $connection = 'client';

    protected $fillable = ['name', 'address', 'country', 'city', 'logo', 'phone', 'email'];
}
