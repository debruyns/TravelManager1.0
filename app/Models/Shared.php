<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shared extends Model {

    protected $table = 'shared';

    protected $fillable = [
        'trip',
        'user',
        'readonly'
    ];

}
