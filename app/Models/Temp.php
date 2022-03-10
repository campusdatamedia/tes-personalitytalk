<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Temp extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'temps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'json'
    ];
}
