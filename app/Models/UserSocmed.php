<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocmed extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_socmeds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform', 'account'
    ];
}
