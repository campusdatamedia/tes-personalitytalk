<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StifinAim extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stifin_aims';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
