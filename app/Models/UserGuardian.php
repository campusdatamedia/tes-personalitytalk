<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGuardian extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_guardians';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'country_code', 'dial_code', 'phone_number', 'occupation'
    ];
}
