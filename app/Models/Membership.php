<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model {

    protected $table = 'membership';

    protected $fillable = [
        'days',
        'price',
        'active'
    ];

}
