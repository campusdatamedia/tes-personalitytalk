<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'verification';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_verification';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_user',
        'token',
        'status',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}