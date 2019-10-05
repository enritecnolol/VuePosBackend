<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDatabase extends Model
{
    protected $connection = 'main';
    protected $table = 'user_databases';
    protected $guarded = [];

}
