<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempTes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'temp_tes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_temp';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_user', 'json', 'temp_at'
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
