<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {

    protected $table = 'payments';

    protected $fillable = [
        'transaction',
        'user',
        'previous',
        'new',
        'days'
    ];

}
