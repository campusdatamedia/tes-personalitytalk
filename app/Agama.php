<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agama';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_agama';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'nama_agama',
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
