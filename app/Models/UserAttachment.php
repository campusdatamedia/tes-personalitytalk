<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAttachment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_attachments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file'
    ];
}
