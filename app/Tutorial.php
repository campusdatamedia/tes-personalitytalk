<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tutorial';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_tutorial';

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    protected $fillable = [
        'id_paket',
		'id_tes',
		'tutorial'
	];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
