<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tes';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_tes';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'nama_tes', 'path', 'waktu_tes'
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
