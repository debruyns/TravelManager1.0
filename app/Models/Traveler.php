<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traveler extends Model {

    protected $table = 'travelers';

    protected $fillable = [
        'trip',
        'firstname',
        'lastname'
    ];

}
