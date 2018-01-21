<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recovery extends Model {

    protected $table = 'recovery';

    protected $fillable = [
        'user',
        'code'
    ];

}
