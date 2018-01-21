<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = 'users';

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'secret',
        'language',
        'active',
        'status',
        'last_login',
        'premium',
        'twofactor'
    ];

}
