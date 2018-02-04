<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model {

    protected $table = 'trips';

    protected $fillable = [
        'name',
        'start',
        'stop',
        'active',
        'owner',
        'type',
        'phase'
    ];

}
